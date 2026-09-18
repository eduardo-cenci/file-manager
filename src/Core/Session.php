<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Core;

final class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => getenv('APP_ENV') === 'production',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        session_start();
    }

    public static function destroy(): void
    {
        $_SESSION = [];

        session_destroy();
    }
}
