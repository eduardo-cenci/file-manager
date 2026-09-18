<?php

declare(strict_types=1);

use League\Plates\Engine;

$templateEngine = new Engine(__DIR__ . '/../views');

$templateEngine->registerFunction('fetchIcon', function (string $iconName): string {
    $iconPath = __DIR__ . "/../views/icons/{$iconName}.svg";

    return is_file($iconPath) ? file_get_contents($iconPath) : '';
});

$templateEngine->registerFunction('readableBytes', function (int $bytes): string {
    $units = ['B', 'KB', 'MB'];
    $unit = $bytes > 0 ? min((int) log($bytes, 1000), count($units) - 1) : 0;

    return round($bytes / (1000 ** $unit)) . ' ' . $units[$unit];
});

return $templateEngine;
