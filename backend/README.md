# API PHP — Horizon-Secur

Projet PHP minimal organise en couches (public, routes, controllers, services, repositories, models).

## Installation

```bash
cd backend
composer install
cp .env.example .env
```

## Lancement

Configurer le serveur web pour ne servir **que** le dossier `public/`.

Exemple avec le serveur integre PHP :

```bash
php -S localhost:8000 -t public
```

Tester : `GET http://localhost:8000/api/health`

## Arborescence

| Dossier | Role |
|---------|------|
| `public/` | Point d'entree web (front controller) |
| `config/` | Configuration application et BDD |
| `routes/` | Declaration des URLs |
| `src/Core/` | Framework minimal (Router, Database, etc.) |
| `src/Controllers/` | Points d'entree HTTP |
| `src/Services/` | Logique metier |
| `src/Repositories/` | Acces aux donnees (SQL) |
| `src/Models/` | Representation des entites |
| `src/Middleware/` | Filtres transverses (auth, etc.) |
| `database/` | Scripts SQL (schema, seed) |
| `storage/logs/` | Fichiers de log |
| `postman/` | Collections et environnements Postman |
