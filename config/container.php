<?php

declare(strict_types=1);

use DI\Container;
use League\Plates\Engine;

return new Container([
    PDO::class => require __DIR__ . '/../config/pdo.php',
    Engine::class => require __DIR__ . '/../config/engine.php',
]);
