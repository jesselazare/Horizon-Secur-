<?php

declare(strict_types=1);

use App\Controllers\HealthController;
use App\Controllers\VoyageController;

/** @var App\Core\Router $router */


// Module 1 — Recherche et réservation de voyages
$router->get('/api/voyages', [VoyageController::class, 'index']);
$router->get('/api/voyage', [VoyageController::class, 'show']);
