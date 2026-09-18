<?php

declare(strict_types=1);

$dbHost = getenv('DB_HOST');
$dbName = getenv('DB_DATABASE');
$dbUser = getenv('DB_USERNAME');
$dbPass = getenv('DB_PASSWORD');

$pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);

$pdo->exec("SET time_zone = '-03:00'");

return $pdo;
