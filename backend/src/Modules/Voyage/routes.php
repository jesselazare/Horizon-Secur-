<?php

declare(strict_types=1);

use App\Modules\Voyage\Controllers\VoyageController;

/** @var App\Core\Router $router */

$router->get('/api/voyages', [VoyageController::class, 'index']);
$router->get('/api/voyage', [VoyageController::class, 'show']);
