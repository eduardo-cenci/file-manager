<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Core;

use EduardoCenci\FileManager\Enum\AlertType;
use EduardoCenci\FileManager\Exception\AppException;
use EduardoCenci\FileManager\Exception\HttpException;
use ErrorException;
use League\Plates\Engine;
use Throwable;

final class ErrorHandler
{
    public function __construct(private Engine $templateEngine) {}

    public function register(): void
    {
        ini_set('error_log', __DIR__ . '/../../storage/logs/application/error.log');

        set_error_handler($this->handleError(...));
        set_exception_handler($this->handleException(...));
        register_shutdown_function($this->handleShutdown(...));
    }

    private function handleError(
        int $severity,
        string $message,
        string $file,
        int $line,
    ): void {
        throw new ErrorException($message, 0, $severity, $file, $line);
    }

    private function handleException(Throwable $exception): void
    {
        $this->log(
            $exception->getCode(),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $_SERVER['REQUEST_URI'] ?? '',
        );

        if ($exception instanceof AppException) {
            Router::redirect($exception->redirectTo, $exception->getMessage(), AlertType::Error);
        }

        $statusCode = $exception instanceof HttpException ? $exception->statusCode : 500;
        $message = $exception instanceof HttpException ? $exception->getMessage() : null;

        $this->respond($statusCode, $message);
    }

    private function handleShutdown(): void
    {
        $error = error_get_last();
        $fatalErrors = [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE];

        if ($error === null || !in_array($error['type'], $fatalErrors, true)) {
            return;
        }

        $this->log(
            $error['type'],
            $error['message'],
            $error['file'],
            $error['line'],
            $_SERVER['REQUEST_URI'] ?? '',
        );

        $this->respond(500, null);
    }

    private function log(
        int|string $code,
        string $message,
        string $file,
        int $line,
        string $url,
    ): void {
        $summary = "Error: [$code] $message in $file on line $line. $url";

        error_log($summary);
    }

    private function respond(
        int $statusCode,
        ?string $errorMessage,
    ): void {
        http_response_code($statusCode);

        echo $this->templateEngine->render('pages/error', compact('errorMessage'));

        exit;
    }
}
