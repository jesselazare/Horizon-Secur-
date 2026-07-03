# API PHP — Horizon-Secur

Backend PHP organisé en couches. Le **Module 1 (Voyages)** est implémenté comme exemple à suivre.

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

## Endpoints disponibles

| Méthode | URL | Description |
|---------|-----|-------------|
| GET | `/api/health` | Vérifier que l'API fonctionne |
| GET | `/api/voyages` | Lister tous les voyages |
| GET | `/api/voyages?destination=...&budget_max=...&date_depart=...` | Rechercher (RG-01) |
| GET | `/api/voyage?id=1` | Détail d'un voyage |

## Structure

| Dossier | Rôle |
|---------|------|
| `public/` | Point d'entrée web |
| `config/` | Configuration |
| `routes/` | Routes API |
| `src/Core/` | Router, Database, Response |
| `src/Controllers/` | Contrôleurs HTTP |
| `src/Services/` | Logique métier |
| `src/Repositories/` | Accès SQL |
| `src/Models/` | Entités |
| `database/` | Schéma SQL + données de test |
| `scripts/setup_db.php` | Initialiser la base de données |

Lire `documentation.md` pour le détail de chaque fichier.

## Prochaines étapes (stagiaire)

Reproduire le pattern du Module 1 pour les modules suivants :
- M2 — Espace Client
- M3 — Moteur Anti-Fraude
- M4 — Back-Office Agent
