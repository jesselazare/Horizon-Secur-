<?php

declare(strict_types=1);

$baseDir = dirname(__DIR__);

function runSqlFile(PDO $pdo, string $file): void
{
    $sql = file_get_contents($file);
    if ($sql === false) {
        throw new RuntimeException("Lecture impossible : {$file}");
    }

    $sql = preg_replace('/--.*$/m', '', $sql) ?? $sql;
    $pdo->exec('SET FOREIGN_KEY_CHECKS=0');

    foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
        if ($statement === '') {
            continue;
        }
        $pdo->exec($statement);
    }

    $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
    echo 'OK: ' . basename($file) . PHP_EOL;
}

$pdo = new PDO('mysql:host=127.0.0.1;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$pdo->exec('DROP DATABASE IF EXISTS billeterie_voyage');
$pdo->exec('CREATE DATABASE billeterie_voyage CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
$pdo->exec('USE billeterie_voyage');

runSqlFile($pdo, $baseDir . '/database/billeterie_voyage.sql');
runSqlFile($pdo, $baseDir . '/database/seed.sql');

echo 'Voyages : ' . $pdo->query('SELECT COUNT(*) FROM voyage')->fetchColumn() . PHP_EOL;
echo 'Agents  : ' . $pdo->query('SELECT COUNT(*) FROM agent_interne')->fetchColumn() . PHP_EOL;