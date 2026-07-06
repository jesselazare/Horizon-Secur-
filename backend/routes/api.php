<?php

declare(strict_types=1);

/** @var App\Core\Router $router */

$modulesPath = dirname(__DIR__) . '/src/Modules';

foreach (glob($modulesPath . '/*/routes.php') ?: [] as $routesFile) {
    require $routesFile;
}