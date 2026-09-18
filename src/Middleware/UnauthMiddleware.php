<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Middleware;

use EduardoCenci\FileManager\Core\Router;

final class UnauthMiddleware extends Middleware
{
    public function handle(): void
    {
        if (isset($_SESSION['user_id'])) {
            Router::redirect('/');
            exit;
        }
    }
}
