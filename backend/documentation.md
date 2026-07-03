# Documentation — API PHP Horizon-Secur

## Architecture modulaire

```
Requête HTTP
    → public/index.php
    → config/bootstrap.php
    → routes/api.php              (charge tous les modules)
    → Modules/{Nom}/routes.php
    → Modules/{Nom}/Controllers/
    → Modules/{Nom}/Services/
    → Modules/{Nom}/Repositories/
    → Modules/{Nom}/Models/
    → Response JSON
```

## Arborescence

```
backend/
├── config/
├── database/
├── public/
├── routes/
│   └── api.php                 # Charge automatiquement les routes des modules
├── scripts/
├── src/
│   ├── Core/                   # Framework minimal (partagé)
│   └── Modules/
│       ├── Voyage/             # M1 — Exemple complet
│       ├── Utilisateur/        # M2 — À développer
│       ├── Fraude/             # M3 — À développer
│       └── Agent/              # M4 — À développer
├── documentation.md
└── README.md
```

## Structure d'un module

Chaque module est autonome et contient tout ce qui lui est nécessaire :

```
src/Modules/Voyage/
├── Controllers/
│   └── VoyageController.php    # namespace App\Modules\Voyage\Controllers
├── Models/
│   └── Voyage.php              # namespace App\Modules\Voyage\Models
├── Repositories/
│   └── VoyageRepository.php    # namespace App\Modules\Voyage\Repositories
├── Services/
│   └── VoyageService.php       # namespace App\Modules\Voyage\Services
└── routes.php                  # Déclaration des routes du module
```

### `routes.php` du module

```php
use App\Modules\Voyage\Controllers\VoyageController;

/** @var App\Core\Router $router */
$router->get('/api/voyages', [VoyageController::class, 'index']);
```

### Chargement automatique

Le fichier `routes/api.php` parcourt tous les modules :

```php
foreach (glob($modulesPath . '/*/routes.php') as $routesFile) {
    require $routesFile;
}
```

---

## Core (partagé, ne pas mettre de logique métier)

| Fichier | Rôle |
|---------|------|
| `Router.php` | Routage HTTP + middleware |
| `Database.php` | Connexion PDO |
| `Response.php` | Réponses JSON |
| `Controller.php` | Classe de base des contrôleurs |

---

## Modules

| Module | Dossier | Statut | Description |
|--------|---------|--------|-------------|
| M1 — Voyages | `Voyage/` | Fait (exemple) | Recherche et consultation |
| M2 — Client | `Utilisateur/` | À faire | Compte, profil, historique |
| M3 — Anti-fraude | `Fraude/` | À faire | Score, détection, blocage |
| M4 — Agent | `Agent/` | À faire | Back-office administrateur |

---

## Créer un nouveau module

1. Créer le dossier `src/Modules/MonModule/`
2. Ajouter `Controllers/`, `Models/`, `Repositories/`, `Services/`
3. Créer `routes.php` avec les endpoints
4. Le module est chargé automatiquement (pas de modification de `api.php`)

**Namespaces :** `App\Modules\MonModule\{Couche}\{Classe}`

---

## Base de données

| Table | Champs diagramme | Champs ajoutés |
|-------|-----------------|----------------|
| `utilisateur` | id, statut | nom, prenom, email, password, adresse, date_inscription |
| `voyage` | id, destination, date_depart, prix_par_personne | capacite_max, titre, pays, description, image_url |
| `voyageur` | id, num_passport, temps_saisie | nom, prenom, age, sexe, adresse |
| `reservation` | id, reference, statut | date_reservation, id_utilisateur, id_voyage |
| `reservation_voyageur` | — | liaison reservation ↔ voyageur |
| `paiement` | id, pays_emission_carte, dates, statut | montant, methode_paiement, id_reservation |
| `alerte_fraude` | tous les champs diagramme | date_detection, id_paiement, id_agent_interne |
| `agent_interne` | id | nom, prenom, email, password, statut (tout agent = admin) |

---

## Conventions

| Couche | Responsabilité |
|--------|---------------|
| **Controller** | HTTP, appelle le Service |
| **Service** | Logique métier, validations |
| **Repository** | Requêtes SQL |
| **Model** | Structure de données |

---

## Commandes

```bash
cd backend
composer install
cp .env.example .env
php scripts/setup_db.php
composer dump-autoload
php -S localhost:8000 -t public
```
