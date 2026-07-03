# Guide stagiaire — Backend Horizon-Secur

Salut jess,

Je t'explique comment j'ai mis en place le backend et comment le projet est structuré. Le **Module 1 (Voyage)** sert d'exemple : ton travail consistera à reproduire la même logique pour les modules suivants.

---

## Ce que j'ai voulu faire

J'ai construit une **API PHP** simple, mais avec une structure claire et professionnelle. L'objectif est que chaque fonctionnalité métier vive dans **son propre module**, indépendant des autres.

Le module Voyage est terminé. Les prochains modules à développer sont :

| Module | Dossier | Rôle |
|--------|---------|------|
| **M2 — Client** | `Utilisateur/` | Compte, profil, historique |
| **M3 — Anti-fraude** | `Fraude/` | Score, détection, blocage |
| **M4 — Agent** | `Agent/` | Back-office administrateur |

---

## Comment une requête traverse le projet

Quand quelqu'un appelle `GET /api/voyages`, voici le chemin :

```
Navigateur / Postman
    → public/index.php          (point d'entrée unique)
    → config/bootstrap.php      (config + connexion BDD)
    → routes/api.php            (charge tous les modules)
    → Modules/Voyage/routes.php (déclare les URLs du module)
    → VoyageController          (lit les paramètres HTTP)
    → VoyageService             (règles métier)
    → VoyageRepository          (requêtes SQL)
    → Voyage (Model)            (structure des données)
    → Réponse JSON
```

Tu n'as **jamais** besoin de modifier `public/index.php` ni `routes/api.php` pour ajouter un module. Il suffit de créer un dossier dans `src/Modules/` avec un fichier `routes.php` : il sera chargé automatiquement.

---

## L'arborescence du projet

```
backend/
├── config/              → Configuration (app, BDD, bootstrap)
├── database/
│   ├── billeterie_voyage.sql   → Schéma complet (8 tables)
│   └── seed.sql                → Données de test
├── public/
│   └── index.php        → Seul fichier accessible depuis le web
├── routes/
│   └── api.php          → Charge automatiquement les routes de chaque module
├── scripts/
│   ├── setup_db.php     → Crée la BDD + importe le schéma + seed
│   └── verify_db.php    → Vérifie que la BDD est conforme
├── postman/
│   └── collections/     → Tests API (à compléter pour ton module)
├── src/
│   ├── Core/            → Infrastructure partagée (NE PAS y mettre de logique métier)
│   │   ├── Router.php
│   │   ├── Database.php
│   │   ├── Response.php
│   │   └── Controller.php
│   └── Modules/
│       └── Voyage/      → Module 1 (ton modèle à suivre)
│           ├── Controllers/
│           ├── Models/
│           ├── Repositories/
│           ├── Services/
│           └── routes.php
├── composer.json        → Autoload PSR-4 : App\ → src/
└── .env                 → Identifiants BDD (ne pas committer)
```

---

## Le rôle de chaque couche (dans un module)

Je sépare toujours le code en **4 couches**. C'est la règle à respecter :

| Couche | Fichier | Responsabilité |
|--------|---------|----------------|
| **Controller** | `VoyageController.php` | Reçoit la requête HTTP, lit `$_GET` / le body JSON, appelle le Service, renvoie la réponse |
| **Service** | `VoyageService.php` | Règles métier, validations, calculs (ex. places restantes) |
| **Repository** | `VoyageRepository.php` | Requêtes SQL uniquement — pas de logique métier |
| **Model** | `Voyage.php` | Représente une entité (propriétés + `fromRow()` / `toArray()`) |

**Règle d'or :** le Controller ne fait pas de SQL. Le Repository ne valide pas les règles métier. Le Service ne connaît pas `$_GET`.

---

## Ce que j'ai fait dans le Module Voyage (ton référentiel)

### Endpoints

| Méthode | URL | Rôle |
|---------|-----|------|
| GET | `/api/voyages` | Liste tous les voyages |
| GET | `/api/voyages?destination=...&budget_max=...&date_depart=...` | Recherche (RG-01) |
| GET | `/api/voyage?id=1` | Détail + disponibilité (RG-02) |

### Règles métier implémentées

- **RG-01** : la recherche exige les 3 critères (`destination`, `budget_max`, `date_depart`). Si aucun filtre n'est passé, on retourne tout.
- **RG-02** : le détail calcule `places_restantes` et `disponible` à partir des réservations existantes.

### Namespaces

Tout suit le pattern `App\Modules\{NomModule}\{Couche}\{Classe}`.

Exemple : `App\Modules\Voyage\Services\VoyageService`

---

## Comment créer ton prochain module (ex. Utilisateur)

1. Créer le dossier `src/Modules/Utilisateur/`
2. Ajouter les sous-dossiers : `Controllers/`, `Models/`, `Repositories/`, `Services/`
3. Créer `routes.php` à la racine du module :

```php
use App\Modules\Utilisateur\Controllers\UtilisateurController;

/** @var App\Core\Router $router */
$router->post('/api/utilisateur/inscription', [UtilisateurController::class, 'register']);
```

4. Implémenter les classes en suivant le même schéma que Voyage
5. Lancer `composer dump-autoload`
6. Ajouter les requêtes dans `postman/collections/api.request.yaml`
7. Tester

**Tu n'as pas besoin de toucher à `routes/api.php`** — il charge déjà tous les `routes.php` des modules automatiquement.

---

## La base de données

J'ai reconstruit le schéma à partir du **diagramme de classes** (`database/diagramme de classe.jpeg`). Il y a 8 tables :

- `utilisateur`, `voyage`, `voyageur`, `reservation`, `reservation_voyageur`, `paiement`, `alerte_fraude`, `agent_interne`

Pour remettre la BDD à zéro :

```bash
cd backend
php scripts/setup_db.php
```

Pour vérifier que tout est conforme :

```bash
php scripts/verify_db.php
```

---

## Ce que j'utilise dans `Core/` (partagé, ne pas modifier sauf besoin global)

| Fichier | Rôle |
|---------|------|
| `Router.php` | Enregistre les routes GET/POST et dispatche la requête |
| `Database.php` | Connexion PDO singleton (lit le `.env`) |
| `Response.php` | Format JSON uniforme : `{ "success": true/false, "data": ... }` ou `{ "success": false, "message": "..." }` |
| `Controller.php` | Classe de base avec `json()`, `input()` et helpers pour extraire les champs du body |

---

## Comment lancer et tester

```bash
cd backend
composer install
cp .env.example .env        # puis adapter les identifiants MySQL
php scripts/setup_db.php
composer dump-autoload
php -S localhost:8000 -t public
```

Ensuite, tester avec Postman ou directement dans le navigateur :

- `http://localhost:8000/api/voyages`
- `http://localhost:8000/api/voyage?id=1`

---

## Ce que j'attends de toi

1. **Copier la structure du module Voyage** — même organisation des dossiers, mêmes conventions de nommage
2. **Respecter la séparation des couches** — Controller → Service → Repository → Model
3. **Mettre la logique métier dans le Service**, pas ailleurs
4. **Tester avec Postman** avant de me montrer le résultat
5. **Ne pas toucher à `Core/`** sauf si on en discute ensemble

Si tu bloques, commence toujours par relire le module `Voyage/` : c'est le modèle que j'ai voulu pour tout le projet.

---

Pour le détail technique (conventions, tables, commandes), voir aussi `documentation.md`.
