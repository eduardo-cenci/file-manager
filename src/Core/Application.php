<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Core;

final class Application
{
    public static function run(): void
    {
        $diContainer = require __DIR__ . '/../../config/container.php';

        $errorHandler = $diContainer->get(ErrorHandler::class);
        $errorHandler->register();

        Session::start();

        $router = $diContainer->get(Router::class);
        $router->dispatch(Request::fromGlobals());
    }
}
