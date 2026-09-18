<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Core;

use EduardoCenci\FileManager\Enum\AlertType;
use EduardoCenci\FileManager\Exception\HttpException;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Psr\Container\ContainerInterface;

use function FastRoute\simpleDispatcher;

final class Router
{
    private readonly Dispatcher $dispatcher;

    public function __construct(private readonly ContainerInterface $diContainer)
    {
        $this->dispatcher = simpleDispatcher(
            static function (RouteCollector $collector): void {
                $routes = require __DIR__ . '/../../config/routes.php';

                foreach ($routes as $route) {
                    $collector->addRoute(...$route);
                }
            },
        );
    }

    public function dispatch(Request $request): void
    {
        $routeInfo = $this->dispatcher->dispatch($request->method, $request->path);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw new HttpException(404, 'Not found');
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new HttpException(405, 'Method not allowed');
            default:
                $this->handle($routeInfo[1], $routeInfo[2]);
        }
    }

    private function handle(
        array $handler,
        array $vars,
    ): void {
        [$controllerClass, $action] = $handler[0];
        $middlewares = $handler[1] ?? [];

        foreach ($middlewares as $middlewareClass) {
            $middleware = $this->diContainer->get($middlewareClass);
            $middleware->handle();
        }

        $controller = $this->diContainer->get($controllerClass);
        $controller->$action(...$vars);
    }

    public static function redirect(
        string $location,
        ?string $alertMessage = null,
        AlertType $alertType = AlertType::Info,
    ): void {
        $_SESSION['alert_message'] = $alertMessage;
        $_SESSION['alert_type'] = $alertType->value;

        header("Location: {$location}");

        exit;
    }
}
