<?php

declare(strict_types=1);

// 1. Charger l'autoloader Composer
$autoload = dirname(__DIR__) . '/vendor/autoload.php';

if (!is_file($autoload)) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Exécutez "composer install" à la racine du projet.',
    ]);
    exit(1);
}

require $autoload;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, Accept');

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// 2. Charger la configuration
require dirname(__DIR__) . '/config/bootstrap.php';

// 3. Créer le Router
$router = new App\Core\Router();

// 4. Charger routes/api.php
require dirname(__DIR__) . '/routes/api.php';

// 5. Dispatcher la requête
$router->dispatch();
