<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Middleware;

use EduardoCenci\FileManager\Exception\HttpException;

final class CsrfMiddleware extends Middleware
{
    public function handle(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $_SESSION['csrf_token'] ??= bin2hex(random_bytes(16));
            return;
        } elseif (
            !isset($_POST['csrf_token'])
            || !isset($_SESSION['csrf_token'])
            || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            throw new HttpException(403, 'CSRF validation failed');
        }
    }
}
