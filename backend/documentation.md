# Documentation — API PHP Horizon-Secur

## Vue d'ensemble

```
Requête HTTP
    → public/index.php       (point d'entrée)
    → config/bootstrap.php   (configuration)
    → routes/api.php         (routage)
    → Controller             (réception HTTP)
    → Service                (logique métier)
    → Repository             (accès base de données)
    → Model                  (représentation des données)
    → Response JSON          (réponse au client)
```

## Arborescence

```
backend/
├── config/                 Configuration application et BDD
├── database/               Schéma SQL + données de test
├── postman/                Tests API (optionnel)
├── public/                 Point d'entrée web (document root)
├── routes/                 Déclaration des routes
├── scripts/setup_db.php    Script d'initialisation de la BDD
├── src/
│   ├── Controllers/        Contrôleurs HTTP
│   ├── Core/               Router, Database, Response, Controller
│   ├── Middleware/         (vide — à utiliser pour M2/M4)
│   ├── Models/             Entités
│   ├── Repositories/       Requêtes SQL
│   └── Services/           Logique métier
├── storage/logs/           Logs applicatifs
├── .env.example            Modèle de configuration
├── composer.json           Dépendances PHP
├── documentation.md        Ce fichier
└── README.md               Guide de démarrage
```

---

## Fichiers essentiels

### `composer.json`
- Dépendance PHP >= 8.1
- Autoload PSR-4 : namespace `App\` → dossier `src/`
- Installation : `composer install`

### `.env` / `.env.example`
- Copier `.env.example` en `.env` et adapter les valeurs
- Variables BDD : `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`
- **Ne jamais commiter le fichier `.env`**

### `public/index.php`
Point d'entrée unique. Il charge Composer, la configuration, le routeur, puis dispatch la requête.

### `routes/api.php`
Déclare toutes les routes. C'est ici qu'on ajoute les nouveaux endpoints.

### `scripts/setup_db.php`
Crée la base `billeterie_voyage`, importe le schéma et les données de test.

---

## Couches du code (`src/`)

### Core — Infrastructure (ne pas modifier sauf besoin technique)

| Fichier | Rôle |
|---------|------|
| `Router.php` | Associe URL + méthode HTTP → contrôleur |
| `Database.php` | Connexion PDO unique (Singleton) |
| `Response.php` | Envoie les réponses JSON (`success`, `data`, `message`) |
| `Controller.php` | Classe de base : `json()`, `input()`, extraction des champs |

### Module 1 — Voyages (exemple à reproduire)

| Fichier | Rôle |
|---------|------|
| `Models/Voyage.php` | Objet PHP représentant un voyage |
| `Repositories/VoyageRepository.php` | Requêtes SQL (liste, recherche, détail) |
| `Services/VoyageService.php` | Règles métier RG-01 et RG-02 |
| `Controllers/VoyageController.php` | Endpoints `GET /api/voyages` et `GET /api/voyage` |

### HealthController
Contrôleur simple pour vérifier que l'API répond (`GET /api/health`).

---

## Endpoints actuels

| Méthode | URL | Fichier responsable |
|---------|-----|---------------------|
| GET | `/api/health` | `HealthController::index` |
| GET | `/api/voyages` | `VoyageController::index` |
| GET | `/api/voyage?id=X` | `VoyageController::show` |

### Recherche de voyages (RG-01)
Les 3 paramètres sont obligatoires ensemble :
- `destination` — texte (recherche partielle)
- `budget_max` — prix maximum
- `date_depart` — format `AAAA-MM-JJ`

Exemple : `/api/voyages?destination=Lisbonne&budget_max=500&date_depart=2026-07-20`

Sans paramètre : retourne tous les voyages.

---

## Base de données

| Fichier | Rôle |
|---------|------|
| `database/billeterie_voyage.sql` | Schéma complet (tables, clés, contraintes) |
| `database/seed.sql` | 5 voyages de test |

Tables principales : `voyage`, `reservation`, `utilisateur`, `paiement`, `alerte_antifraude`, etc.

---

## Conventions à respecter

| Couche | Fait | Ne fait pas |
|--------|------|-------------|
| **Controller** | Reçoit HTTP, appelle le Service, renvoie JSON | SQL direct, logique métier |
| **Service** | Règles métier, validations | Accès HTTP, SQL direct |
| **Repository** | Requêtes SQL (CRUD) | Logique métier, JSON |
| **Model** | Structure de données | SQL, logique métier |

---

## Comment ajouter un nouveau module

En suivant l'exemple du Module 1 (Voyages) :

1. Créer `src/Models/MaEntite.php`
2. Créer `src/Repositories/MaEntiteRepository.php`
3. Créer `src/Services/MaEntiteService.php`
4. Créer `src/Controllers/MaEntiteController.php`
5. Déclarer les routes dans `routes/api.php`
6. Ajouter les requêtes de test dans `postman/collections/api.request.yaml`

---

## Modules à développer

| Module | Description | Priorité |
|--------|-------------|----------|
| **M1 — Voyages** | Recherche et consultation | Fait (exemple) |
| **M2 — Espace Client** | Compte, profil, historique | À faire |
| **M3 — Anti-Fraude** | Score, détection, blocage | À faire |
| **M4 — Back-Office** | Tableau de bord agent | À faire |

---

## Commandes utiles

```bash
cd backend
composer install
cp .env.example .env
php scripts/setup_db.php
php -S localhost:8000 -t public
```
