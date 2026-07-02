# Documentation — API PHP Horizon-Secur

Ce document décrit l'architecture du backend, le rôle de chaque dossier et fichier, ainsi que leur utilité dans le cycle de vie d'une requête HTTP.

---

## Vue d'ensemble

Le projet suit une architecture en couches inspirée d'un framework minimal :

```
Requête HTTP
    → public/index.php          (point d'entrée)
    → config/bootstrap.php      (configuration)
    → routes/api.php            (routage)
    → Middleware                (filtres : auth, etc.)
    → Controller                (réception HTTP)
    → Service                   (logique métier)
    → Repository                (accès base de données)
    → Model                     (représentation des données)
    → Response JSON             (réponse au client)
```

---

## Arborescence complète

```
backend/
├── .postman/                   # Configuration workspace Postman
├── config/                     # Fichiers de configuration
├── database/                   # Scripts SQL
├── postman/                    # Collections et environnements API
├── public/                     # Point d'entrée web (document root)
├── routes/                     # Déclaration des routes API
├── src/
│   ├── Controllers/            # Contrôleurs HTTP
│   ├── Core/                   # Classes fondamentales du framework
│   ├── Middleware/             # Filtres transverses
│   ├── Models/                 # Entités / objets métier
│   ├── Repositories/           # Accès aux données (SQL)
│   └── Services/               # Logique métier
├── storage/
│   └── logs/                   # Fichiers de log applicatifs
├── vendor/                     # Dépendances Composer (généré)
├── .env                        # Variables d'environnement (local, non versionné)
├── .env.example                # Modèle des variables d'environnement
├── .gitignore                  # Fichiers ignorés par Git
├── composer.json               # Dépendances et autoload PHP
├── composer.lock               # Versions figées des dépendances
├── documentation.md            # Ce fichier
└── README.md                   # Guide de démarrage rapide
```

---

## Fichiers à la racine

### `composer.json`

**Rôle :** Fichier de configuration Composer.

**Utilité :**
- Déclare les dépendances PHP du projet (ici : PHP >= 8.1)
- Configure l'autoload PSR-4 : le namespace `App\` pointe vers le dossier `src/`
- Permet d'installer les dépendances avec `composer install`

### `composer.lock`

**Rôle :** Verrouillage des versions des dépendances.

**Utilité :**
- Généré automatiquement par Composer
- Garantit que tous les développeurs utilisent les mêmes versions
- Ne pas modifier manuellement

### `.env.example`

**Rôle :** Modèle des variables d'environnement.

**Utilité :**
- Documente les variables nécessaires au fonctionnement de l'API
- À copier en `.env` lors de l'installation (`cp .env.example .env`)
- Variables disponibles :
  - `APP_NAME` : nom de l'application
  - `APP_DEBUG` : mode debug (true/false)
  - `APP_KEY` : clé secrète pour signer les tokens d'authentification
  - `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`, `DB_CHARSET` : connexion MySQL

### `.env`

**Rôle :** Configuration locale réelle (non versionnée).

**Utilité :**
- Contient les vraies valeurs de connexion BDD et clés secrètes
- Chargé automatiquement par `config/bootstrap.php`
- **Ne jamais commiter ce fichier** (présent dans `.gitignore`)

### `.gitignore`

**Rôle :** Liste des fichiers/dossiers exclus du dépôt Git.

**Utilité :**
- Ignore `vendor/` (réinstallable via Composer)
- Ignore `.env` (données sensibles)
- Ignore les fichiers de log dans `storage/logs/`

### `README.md`

**Rôle :** Guide de démarrage rapide.

**Utilité :**
- Instructions d'installation (`composer install`, copie du `.env`)
- Commande pour lancer le serveur de développement
- Tableau récapitulatif des dossiers principaux

### `documentation.md`

**Rôle :** Documentation complète du projet (ce fichier).

**Utilité :**
- Référence détaillée pour comprendre l'architecture
- Description de chaque dossier et fichier

---

## Dossier `public/`

C'est le **seul dossier exposé au web**. Le serveur Apache/Nginx ou `php -S` doit pointer ici.

### `public/index.php`

**Rôle :** Front controller — point d'entrée unique de toutes les requêtes.

**Utilité :**
1. Charge l'autoloader Composer (`vendor/autoload.php`)
2. Configure les en-têtes CORS (Cross-Origin Resource Sharing)
3. Gère les requêtes preflight `OPTIONS`
4. Charge la configuration via `config/bootstrap.php`
5. Instancie le `Router`
6. Charge les routes depuis `routes/api.php`
7. Dispatch la requête vers le bon contrôleur

### `public/.htaccess`

**Rôle :** Configuration Apache pour le routage.

**Utilité :**
- Redirige toutes les requêtes vers `index.php` (sauf les fichiers existants)
- Permet d'utiliser des URLs propres (`/api/health` au lieu de `/index.php?route=...`)
- Nécessite `mod_rewrite` activé sur Apache

---

## Dossier `config/`

Contient toute la configuration applicative, séparée du code métier.

### `config/bootstrap.php`

**Rôle :** Bootstrap de l'application.

**Utilité :**
- Définit la fonction `loadEnv()` qui lit le fichier `.env` (format `KEY=value`)
- Charge `config/app.php` et `config/database.php`
- Configure le fuseau horaire PHP (`Europe/Paris` par défaut)
- Exécuté à chaque requête, avant tout traitement

### `config/app.php`

**Rôle :** Configuration générale de l'application.

**Utilité :**
- Retourne un tableau avec :
  - `name` : nom de l'app (depuis `APP_NAME`)
  - `debug` : mode debug booléen (depuis `APP_DEBUG`)
  - `timezone` : fuseau horaire (depuis `APP_TIMEZONE` ou `Europe/Paris`)

### `config/database.php`

**Rôle :** Paramètres de connexion à la base de données.

**Utilité :**
- Retourne un tableau avec host, nom de BDD, utilisateur, mot de passe, charset
- Lu par `App\Core\Database` pour établir la connexion PDO
- Les valeurs proviennent du fichier `.env`

---

## Dossier `routes/`

### `routes/api.php`

**Rôle :** Déclaration centralisée de toutes les routes API.

**Utilité :**
- Associe une URL + méthode HTTP à un contrôleur et une action
- Exemple actuel : `GET /api/health` → `HealthController::index`
- Supporte les groupes de routes avec middleware (auth, admin, etc.)
- C'est ici qu'on ajoute les nouvelles routes au fur et à mesure du développement

**Syntaxe :**
```php
$router->get('/api/ressource', [MonController::class, 'index']);
$router->post('/api/ressource', [MonController::class, 'store']);

$router->group(['middleware' => [AuthMiddleware::class]], function ($router) {
    // Routes protégées par authentification
});
```

---

## Dossier `src/Core/`

Classes fondamentales du mini-framework. **Ne pas y mettre de logique métier.**

### `src/Core/Router.php`

**Rôle :** Routeur HTTP.

**Utilité :**
- Enregistre les routes (`get`, `post`, `put`, `delete`)
- Normalise les chemins d'URL
- Exécute la chaîne de middleware avant d'appeler le contrôleur
- Retourne une erreur 404 si la route n'existe pas
- Supporte les groupes de routes avec middleware partagé

### `src/Core/Controller.php`

**Rôle :** Classe de base abstraite pour tous les contrôleurs.

**Utilité :**
- `json($data, $status)` : envoie une réponse JSON formatée
- `input()` : lit et décode le corps JSON de la requête (`php://input`)
- `extractRequiredString($input, $field)` : extrait une chaîne obligatoire
- `extractRequiredInt($input, $field)` : extrait un entier obligatoire
- `extractRequiredFloat($input, $field)` : extrait un nombre décimal obligatoire
- Tous les contrôleurs héritent de cette classe

### `src/Core/Response.php`

**Rôle :** Gestionnaire de réponses HTTP.

**Utilité :**
- `Response::json($data, $status)` : formate et envoie une réponse JSON standardisée
  ```json
  { "success": true, "data": { ... } }
  ```
- `Response::error($message, $status)` : envoie une erreur JSON
  ```json
  { "success": false, "message": "..." }
  ```
- Termine l'exécution du script après envoi (`exit`)

### `src/Core/Database.php`

**Rôle :** Gestionnaire de connexion PDO (Singleton).

**Utilité :**
- `Database::getConnection()` : retourne une instance PDO unique
- Lit la configuration depuis `config/database.php`
- Configure PDO en mode exception et fetch associatif
- Utilisé par les repositories pour exécuter des requêtes SQL

---

## Dossier `src/Controllers/`

Les contrôleurs reçoivent les requêtes HTTP et orchestrent la réponse. **Pas de SQL direct ici.**

### `src/Controllers/HealthController.php`

**Rôle :** Contrôleur de vérification de l'état de l'API.

**Utilité :**
- `index()` : répond `GET /api/health` avec un statut `ok`
- Sert à vérifier que l'API est démarrée et fonctionnelle
- Utile pour les health checks, monitoring, tests Postman

**Convention pour les futurs contrôleurs :**
- Un contrôleur par ressource métier (ex : `VoyageController`, `BilletController`)
- Méthodes CRUD classiques : `index`, `show`, `store`, `update`, `destroy`
- Hérite toujours de `App\Core\Controller`

---

## Dossier `src/Services/`

Contient la **logique métier** (règles de gestion, calculs, validations complexes).

### `src/Services/AuthService.php`

**Rôle :** Service d'authentification par token.

**Utilité :**
- `createToken($userId)` : génère un token Bearer signé (HMAC-SHA256), valide 24h
- `validateToken($token)` : vérifie la signature et l'expiration, retourne l'ID utilisateur
- Utilise `APP_KEY` du `.env` comme clé secrète
- Appelé par `AuthMiddleware` et par le futur `AuthController` (login)

**Convention pour les futurs services :**
- Un service par domaine métier (ex : `BilletService`, `ReservationService`)
- Contient la logique qui ne dépend pas de HTTP ni de SQL directement
- Peut appeler plusieurs repositories

---

## Dossier `src/Middleware/`

Filtres exécutés **avant** le contrôleur, sur certaines routes.

### `src/Middleware/AuthMiddleware.php`

**Rôle :** Vérification de l'authentification Bearer.

**Utilité :**
- Lit l'en-tête `Authorization: Bearer <token>`
- Valide le token via `AuthService::validateToken()`
- Stocke l'ID utilisateur dans `$_SERVER['AUTH_USER_ID']`
- Retourne 401 si le token est absent, invalide ou expiré
- À appliquer sur les routes protégées via `$router->group(['middleware' => [AuthMiddleware::class]], ...)`

**Convention pour les futurs middleware :**
- Une classe avec une méthode `handle(callable $next): void`
- Appelle `$next()` pour passer au middleware/contrôleur suivant
- Bloque la requête avec `Response::error()` si la condition n'est pas remplie

---

## Dossier `src/Models/`

Représentation des **entités métier** (objets PHP miroir des tables SQL).

### `src/Models/.gitkeep`

**Rôle :** Fichier placeholder pour que Git versionne le dossier vide.

**Utilité :**
- Le dossier est prêt à recevoir les modèles
- Exemples à créer : `Voyage.php`, `Billet.php`, `Client.php`, `Administrateur.php`

**Convention pour les futurs modèles :**
- Une classe par table de la base de données
- Propriétés correspondant aux colonnes
- Pas de logique SQL — uniquement des données et éventuellement des accesseurs

---

## Dossier `src/Repositories/`

Couche d'**accès aux données** (requêtes SQL via PDO).

### `src/Repositories/.gitkeep`

**Rôle :** Fichier placeholder pour que Git versionne le dossier vide.

**Utilité :**
- Le dossier est prêt à recevoir les repositories
- Exemples à créer : `VoyageRepository`, `BilletRepository`, `ClientRepository`

**Convention pour les futurs repositories :**
- Une classe par table ou groupe de tables liées
- Utilise `Database::getConnection()` pour obtenir PDO
- Méthodes typiques : `find($id)`, `findAll()`, `create($data)`, `update($id, $data)`, `delete($id)`
- Retourne des objets `Model` ou des tableaux associatifs

---

## Dossier `database/`

Scripts SQL pour initialiser et peupler la base de données.

### `database/billeterie_voyage.sql`

**Rôle :** Schéma complet de la base de données.

**Utilité :**
- Contient la création de toutes les tables du projet Horizon-Secur
- Tables : `administrateur`, `client`, `voyage`, `billet`, etc.
- À importer dans MySQL/phpMyAdmin avant de lancer l'API
- Base cible : `billeterie_voyage` (configurée dans `.env`)

### `database/seed.sql`

**Rôle :** Données de test.

**Utilité :**
- Contient des `INSERT` pour peupler la BDD avec des données fictives
- À importer **après** `billeterie_voyage.sql`
- Facilite le développement et les tests sans saisie manuelle

---

## Dossier `storage/`

Stockage de fichiers générés par l'application (hors base de données).

### `storage/logs/`

**Rôle :** Répertoire des fichiers de log.

**Utilité :**
- Stocke les logs applicatifs (erreurs, requêtes, etc.)
- Le fichier `.gitkeep` permet de versionner le dossier vide
- Les fichiers `*.log` sont ignorés par Git (`.gitignore`)

---

## Dossier `postman/`

Configuration pour tester l'API avec [Postman](https://www.postman.com/) ou Postman CLI.

### `postman/environments/local.environment.yaml`

**Rôle :** Environnement de test local.

**Utilité :**
- Définit la variable `baseUrl` = `http://localhost:8000`
- Utilisée dans les requêtes Postman via `{{baseUrl}}`

### `postman/collections/health.request.yaml`

**Rôle :** Requête de test du health check.

**Utilité :**
- Requête `GET {{baseUrl}}/api/health`
- Permet de valider rapidement que l'API répond

---

## Dossier `.postman/`

### `.postman/resources.yaml`

**Rôle :** Configuration du workspace Postman.

**Utilité :**
- Lie le dossier `postman/` au workspace Postman local
- Les fichiers YAML dans `postman/` sont automatiquement synchronisés

---

## Dossier `vendor/`

**Rôle :** Dépendances PHP installées par Composer.

**Utilité :**
- Contient l'autoloader (`vendor/autoload.php`) et les packages
- Généré par `composer install` — **ne jamais modifier manuellement**
- Ignoré par Git (réinstallable à tout moment)

---

## Flux d'une requête HTTP (exemple)

Requête : `GET http://localhost:8000/api/health`

```
1. Apache/Nginx reçoit la requête
2. .htaccess redirige vers public/index.php
3. index.php charge vendor/autoload.php
4. index.php configure CORS
5. index.php charge config/bootstrap.php
   → lit .env, configure timezone
6. index.php crée new Router()
7. index.php charge routes/api.php
   → enregistre GET /api/health → HealthController::index
8. Router::dispatch()
   → trouve la route, pas de middleware
   → instancie HealthController
   → appelle index()
9. HealthController::json(['status' => 'ok', ...])
10. Response::json() envoie le JSON et termine
```

Requête protégée : `GET /api/voyages` (avec middleware auth)

```
1-7. (identique)
8. Router::dispatch()
   → trouve la route avec AuthMiddleware
   → AuthMiddleware::handle()
      → lit Authorization: Bearer <token>
      → AuthService::validateToken()
      → stocke AUTH_USER_ID
      → appelle $next()
   → instancie VoyageController
   → appelle index()
9. VoyageController appelle VoyageService
10. VoyageService appelle VoyageRepository
11. VoyageRepository exécute SQL via Database::getConnection()
12. Réponse JSON remontée au client
```

---

## Conventions de développement

| Couche | Responsabilité | Interdit |
|--------|---------------|----------|
| **Controller** | Recevoir HTTP, valider l'input, appeler le service, renvoyer JSON | SQL direct, logique métier complexe |
| **Service** | Règles métier, orchestration, validations | Accès HTTP (`$_SERVER`, headers) |
| **Repository** | Requêtes SQL (CRUD) | Logique métier, formatage JSON |
| **Model** | Structure de données | SQL, logique métier |
| **Middleware** | Filtrage transversal (auth, rôles) | Logique métier |
| **Core** | Infrastructure (router, BDD, réponses) | Logique métier spécifique au projet |

---

## Commandes utiles

```bash
# Installation
cd backend
composer install
cp .env.example .env

# Lancer le serveur de développement
php -S localhost:8000 -t public

# Tester l'API
curl http://localhost:8000/api/health

# Régénérer l'autoload après ajout de classes
composer dump-autoload
```

---

## Prochaines étapes de développement

1. Créer les **Models** correspondant aux tables de `billeterie_voyage.sql`
2. Créer les **Repositories** pour chaque entité
3. Créer les **Services** avec la logique métier
4. Créer les **Controllers** et déclarer les routes dans `routes/api.php`
5. Ajouter un `AuthController` (login) qui utilise `AuthService::createToken()`
6. Protéger les routes sensibles avec `AuthMiddleware`
7. Compléter `database/seed.sql` avec des données de test
8. Ajouter les requêtes Postman pour chaque endpoint
