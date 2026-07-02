# QCM — Backend API PHP Horizon-Secur

**Durée suggérée :** 45 minutes  
**Nombre de questions :** 40  
**Barème :** 1 point par question — Total : 40 points

**Consignes :** Pour chaque question, une seule réponse est correcte. Cochez la lettre correspondante (A, B, C ou D).

---

## Partie 1 — Architecture générale (Questions 1 à 5)

### Question 1
Quel est le point d'entrée unique de toutes les requêtes HTTP dans ce projet ?

- A) `routes/api.php`
- B) `config/bootstrap.php`
- C) `public/index.php`
- D) `src/Core/Router.php`

---

### Question 2
Dans quel ordre les couches sont-elles traversées lors d'une requête API complète (avec authentification et accès BDD) ?

- A) Controller → Middleware → Service → Repository → Model
- B) Middleware → Controller → Service → Repository → Model
- C) Repository → Service → Controller → Middleware → Model
- D) Service → Controller → Repository → Middleware → Model

---

### Question 3
Quel dossier est le **seul** exposé au web (document root) ?

- A) `backend/`
- B) `src/`
- C) `public/`
- D) `routes/`

---

### Question 4
Quel namespace PHP est configuré dans `composer.json` pour le dossier `src/` ?

- A) `Horizon\`
- B) `App\`
- C) `Backend\`
- D) `Src\`

---

### Question 5
Quelle version minimale de PHP est requise par ce projet ?

- A) PHP >= 7.4
- B) PHP >= 8.0
- C) PHP >= 8.1
- D) PHP >= 8.3

---

## Partie 2 — Fichiers à la racine (Questions 6 à 10)

### Question 6
À quoi sert le fichier `composer.lock` ?

- A) À lister les routes de l'API
- B) À figer les versions exactes des dépendances installées
- C) À stocker les mots de passe de la base de données
- D) À configurer l'autoload PSR-4 manuellement

---

### Question 7
Quel fichier contient les **vraies** valeurs sensibles (clés, mots de passe BDD) et ne doit **jamais** être commité ?

- A) `.env.example`
- B) `composer.json`
- C) `.env`
- D) `config/database.php`

---

### Question 8
Quelle commande permet d'installer les dépendances PHP du projet ?

- A) `npm install`
- B) `composer install`
- C) `php install`
- D) `composer start`

---

### Question 9
Quels éléments sont ignorés par Git grâce au `.gitignore` ?

- A) Uniquement `vendor/`
- B) `vendor/`, `.env` et les fichiers `*.log` dans `storage/logs/`
- C) Tout le dossier `src/`
- D) Uniquement `.env`

---

### Question 10
À quoi sert la variable `APP_KEY` dans le fichier `.env` ?

- A) À nommer l'application dans les logs
- B) À signer et vérifier les tokens d'authentification
- C) À définir le port du serveur web
- D) À chiffrer les fichiers SQL

---

## Partie 3 — Dossier `public/` et `config/` (Questions 11 à 15)

### Question 11
Que fait le fichier `public/.htaccess` sur Apache ?

- A) Il configure la connexion à MySQL
- B) Il redirige toutes les requêtes vers `index.php` (URLs propres)
- C) Il active le mode debug de l'application
- D) Il charge automatiquement les routes API

---

### Question 12
Quel en-tête HTTP est configuré dans `index.php` pour autoriser les requêtes cross-origin (CORS) ?

- A) `Content-Type: application/json`
- B) `Access-Control-Allow-Origin: *`
- C) `Authorization: Bearer`
- D) `X-Requested-With: XMLHttpRequest`

---

### Question 13
Quel fichier définit la fonction `loadEnv()` et charge `app.php` et `database.php` ?

- A) `config/app.php`
- B) `config/bootstrap.php`
- C) `public/index.php`
- D) `routes/api.php`

---

### Question 14
Quel fuseau horaire est utilisé par défaut si `APP_TIMEZONE` n'est pas défini ?

- A) `UTC`
- B) `Africa/Douala`
- C) `Europe/Paris`
- D) `America/New_York`

---

### Question 15
Quelle classe PHP lit la configuration définie dans `config/database.php` ?

- A) `App\Core\Router`
- B) `App\Core\Response`
- C) `App\Core\Database`
- D) `App\Services\AuthService`

---

## Partie 4 — Routage (Questions 16 à 18)

### Question 16
Dans quel fichier déclare-t-on les routes de l'API ?

- A) `public/index.php`
- B) `routes/api.php`
- C) `src/Core/Router.php`
- D) `config/app.php`

---

### Question 17
Quelle route de test est actuellement configurée dans le projet ?

- A) `GET /api/login`
- B) `GET /api/health`
- C) `POST /api/voyages`
- D) `GET /api/billets`

---

### Question 18
Comment protège-t-on un groupe de routes avec l'authentification ?

- A) `$router->auth('/api/...')`
- B) `$router->group(['middleware' => [AuthMiddleware::class]], function ($router) { ... })`
- C) `$router->secure('/api/...')`
- D) En ajoutant `require 'auth.php'` dans le contrôleur

---

## Partie 5 — Dossier `src/Core/` (Questions 19 à 24)

### Question 19
Quel design pattern utilise la classe `Database` pour garantir une seule connexion PDO ?

- A) Factory
- B) Singleton
- C) Observer
- D) Strategy

---

### Question 20
Quel code HTTP retourne le `Router` si aucune route ne correspond à la requête ?

- A) 401
- B) 403
- C) 404
- D) 500

---

### Question 21
Quelle méthode de `Controller` permet de lire le corps JSON d'une requête POST/PUT ?

- A) `json()`
- B) `input()`
- C) `extractRequiredString()`
- D) `getBody()`

---

### Question 22
Quel est le format JSON standard d'une réponse réussie envoyée par `Response::json()` ?

- A) `{ "status": "ok", "result": ... }`
- B) `{ "success": true, "data": ... }`
- C) `{ "error": false, "payload": ... }`
- D) `{ "code": 200, "body": ... }`

---

### Question 23
Que fait `Response::json()` après avoir envoyé la réponse ?

- A) Il redirige vers une autre route
- B) Il termine l'exécution du script avec `exit`
- C) Il enregistre un log dans `storage/logs/`
- D) Il ferme la connexion PDO

---

### Question 24
Qu'est-il **interdit** de mettre dans le dossier `src/Core/` ?

- A) Le routeur HTTP
- B) La gestion des réponses JSON
- C) La logique métier spécifique au projet
- D) La connexion à la base de données

---

## Partie 6 — Controllers, Services et Middleware (Questions 25 à 30)

### Question 25
Quel contrôleur existe actuellement et à quoi sert-il ?

- A) `AuthController` — gère le login
- B) `HealthController` — vérifie que l'API est opérationnelle
- C) `VoyageController` — liste les voyages
- D) `BilletController` — gère les billets

---

### Question 26
Quelles méthodes CRUD sont les conventions standard pour un contrôleur ?

- A) `create`, `read`, `edit`, `remove`
- B) `index`, `show`, `store`, `update`, `destroy`
- C) `list`, `get`, `add`, `patch`, `delete`
- D) `all`, `one`, `insert`, `modify`, `erase`

---

### Question 27
Quel algorithme utilise `AuthService` pour signer les tokens ?

- A) MD5
- B) SHA-1
- C) HMAC-SHA256
- D) RSA-2048

---

### Question 28
Combien de temps un token généré par `AuthService::createToken()` est-il valide ?

- A) 1 heure
- B) 12 heures
- C) 24 heures
- D) 7 jours

---

### Question 29
Où `AuthMiddleware` stocke-t-il l'identifiant de l'utilisateur authentifié ?

- A) Dans `$_SESSION['user_id']`
- B) Dans `$_SERVER['AUTH_USER_ID']`
- C) Dans un cookie `auth_token`
- D) Dans la base de données

---

### Question 30
Quel code HTTP `AuthMiddleware` retourne-t-il si le token est absent ou invalide ?

- A) 400
- B) 401
- C) 403
- D) 404

---

## Partie 7 — Models, Repositories et Base de données (Questions 31 à 35)

### Question 31
À quoi sert un fichier `.gitkeep` dans un dossier vide ?

- A) À ignorer le dossier dans Git
- B) À permettre à Git de versionner un dossier vide
- C) À stocker les clés API
- D) À générer l'autoload Composer

---

### Question 32
Quelle est la responsabilité principale d'un **Repository** ?

- A) Formater les réponses JSON
- B) Exécuter les requêtes SQL (CRUD) via PDO
- C) Vérifier l'authentification Bearer
- D) Définir les routes de l'API

---

### Question 33
Quelle est la responsabilité principale d'un **Model** ?

- A) Représenter la structure d'une entité (miroir d'une table SQL)
- B) Contenir la logique métier complexe
- C) Router les requêtes HTTP
- D) Charger le fichier `.env`

---

### Question 34
Dans quel ordre faut-il importer les scripts SQL ?

- A) `seed.sql` puis `billeterie_voyage.sql`
- B) `billeterie_voyage.sql` puis `seed.sql`
- C) Les deux en même temps, l'ordre n'a pas d'importance
- D) Uniquement `seed.sql`

---

### Question 35
Quel est le nom de la base de données configurée par défaut dans `.env.example` ?

- A) `horizon_secur`
- B) `app_db`
- C) `billeterie_voyage`
- D) `centre_formation`

---

## Partie 8 — Outils, flux et conventions (Questions 36 à 40)

### Question 36
Quelle commande lance le serveur de développement PHP intégré sur le port 8000 ?

- A) `php server 8000`
- B) `php -S localhost:8000 -t public`
- C) `composer serve`
- D) `php run public/index.php 8000`

---

### Question 37
À quoi sert la variable Postman `{{baseUrl}}` ?

- A) À stocker le token d'authentification
- B) À définir l'URL de base de l'API (ex : `http://localhost:8000`)
- C) À nommer la collection de tests
- D) À configurer la connexion MySQL

---

### Question 38
Dans le flux d'une requête `GET /api/health`, quelle étape suit immédiatement le chargement de `routes/api.php` ?

- A) `AuthMiddleware::handle()`
- B) `Router::dispatch()`
- C) `Database::getConnection()`
- D) `AuthService::validateToken()`

---

### Question 39
Quelle action est **interdite** dans un Controller selon les conventions du projet ?

- A) Appeler un Service
- B) Valider les données d'entrée
- C) Exécuter du SQL directement
- D) Renvoyer une réponse JSON

---

### Question 40
Quelle commande régénère l'autoload Composer après l'ajout d'une nouvelle classe ?

- A) `composer update`
- B) `composer dump-autoload`
- C) `composer refresh`
- D) `php autoload.php`

---

---

# CORRIGÉ (réservé au formateur)

| Q | Réponse | Q | Réponse | Q | Réponse | Q | Réponse |
|---|---------|---|---------|---|---------|---|---------|
| 1 | **C** | 11 | **B** | 21 | **B** | 31 | **B** |
| 2 | **B** | 12 | **B** | 22 | **B** | 32 | **B** |
| 3 | **C** | 13 | **B** | 23 | **B** | 33 | **A** |
| 4 | **B** | 14 | **C** | 24 | **C** | 34 | **B** |
| 5 | **C** | 15 | **C** | 25 | **B** | 35 | **C** |
| 6 | **B** | 16 | **B** | 26 | **B** | 36 | **B** |
| 7 | **C** | 17 | **B** | 27 | **C** | 37 | **B** |
| 8 | **B** | 18 | **B** | 28 | **C** | 38 | **B** |
| 9 | **B** | 19 | **B** | 29 | **B** | 39 | **C** |
| 10 | **B** | 20 | **C** | 30 | **B** | 40 | **B** |

---

## Grille d'évaluation

| Score | Appréciation |
|-------|-------------|
| 36 – 40 | Excellent — maîtrise complète de l'architecture |
| 28 – 35 | Bien — bonnes bases, quelques révisions ciblées |
| 20 – 27 | Passable — revoir la documentation en profondeur |
| < 20 | Insuffisant — reprendre le projet et la documentation |

---

## Thèmes à réviser en cas d'erreur

| Questions ratées | Thème à revoir dans `documentation.md` |
|-----------------|----------------------------------------|
| 1 – 5 | Vue d'ensemble et arborescence |
| 6 – 10 | Fichiers à la racine (`composer`, `.env`, `.gitignore`) |
| 11 – 15 | Dossiers `public/` et `config/` |
| 16 – 18 | Dossier `routes/` |
| 19 – 24 | Dossier `src/Core/` |
| 25 – 30 | Controllers, Services, Middleware |
| 31 – 35 | Models, Repositories, `database/` |
| 36 – 40 | Commandes, flux HTTP, conventions |
