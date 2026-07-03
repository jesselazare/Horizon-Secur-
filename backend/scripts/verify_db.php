<?php

declare(strict_types=1);

$pdo = new PDO('mysql:host=127.0.0.1;dbname=billeterie_voyage;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$expectedTables = [
    'agent_interne',
    'utilisateur',
    'voyage',
    'voyageur',
    'reservation',
    'reservation_voyageur',
    'paiement',
    'alerte_fraude',
];

$tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
$missing = array_diff($expectedTables, $tables);
$extra = array_diff($tables, $expectedTables);

echo 'Tables manquantes : ' . (empty($missing) ? 'aucune' : implode(', ', $missing)) . PHP_EOL;
echo 'Tables en trop   : ' . (empty($extra) ? 'aucune' : implode(', ', $extra)) . PHP_EOL;

$agentCols = $pdo->query('SHOW COLUMNS FROM agent_interne')->fetchAll(PDO::FETCH_COLUMN);
echo 'agent_interne colonnes : ' . implode(', ', $agentCols) . PHP_EOL;
echo 'role present ? ' . (in_array('role', $agentCols, true) ? 'OUI (ERREUR)' : 'non (OK)') . PHP_EOL;

$voyageCols = $pdo->query('SHOW COLUMNS FROM voyage')->fetchAll(PDO::FETCH_COLUMN);
echo 'voyage colonnes : ' . implode(', ', $voyageCols) . PHP_EOL;

echo 'Voyages : ' . $pdo->query('SELECT COUNT(*) FROM voyage')->fetchColumn() . PHP_EOL;
echo 'Agents  : ' . $pdo->query('SELECT COUNT(*) FROM agent_interne')->fetchColumn() . PHP_EOL;
