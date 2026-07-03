# API PHP — Horizon-Secur

Backend PHP en **architecture modulaire**. Chaque module regroupe son code métier.

## Installation

```bash
cd backend
composer install
cp .env.example .env
php scripts/setup_db.php
```

## Lancement

```bash
php -S localhost:8000 -t public
```

## Structure modulaire

```
src/
├── Core/                    # Infrastructure (Router, Database, Response)
└── Modules/
    ├── Voyage/              # M1 — Recherche & réservation (exemple)
    ├── Utilisateur/         # M2 — Espace client (à faire)
    ├── Fraude/              # M3 — Anti-fraude (à faire)
    └── Agent/               # M4 — Back-office (à faire)
```

Chaque module contient :
```
Module/
├── Controllers/
├── Models/
├── Repositories/
├── Services/
└── routes.php               # Routes du module
```

Les routes sont chargées automatiquement depuis `routes/api.php`.

## Endpoints (Module Voyage)

| Méthode | URL |
|---------|-----|
| GET | `/api/voyages` |
| GET | `/api/voyages?destination=...&budget_max=...&date_depart=...` |
| GET | `/api/voyage?id=1` |

Lire `guide.md` pour l'orientation stagiaire et `documentation.md` pour le détail technique.
