<?php

declare(strict_types=1);

use EduardoCenci\FileManager\Controller\AppController;
use EduardoCenci\FileManager\Controller\UserController;
use EduardoCenci\FileManager\Controller\AuthController;
use EduardoCenci\FileManager\Controller\FileController;
use EduardoCenci\FileManager\Controller\FolderController;
use EduardoCenci\FileManager\Middleware\AuthMiddleware;
use EduardoCenci\FileManager\Middleware\CsrfMiddleware;
use EduardoCenci\FileManager\Middleware\UnauthMiddleware;

return [
    ['GET', '/', [[AppController::class, 'workspace'], [CsrfMiddleware::class, AuthMiddleware::class]]],
    ['GET', '/register', [[AppController::class, 'register'], [CsrfMiddleware::class, UnauthMiddleware::class]]],
    ['POST', '/register', [[UserController::class, 'register'], [CsrfMiddleware::class, UnauthMiddleware::class]]],
    ['GET', '/login', [[AppController::class, 'login'], [CsrfMiddleware::class, UnauthMiddleware::class]]],
    ['POST', '/login', [[AuthController::class, 'login'], [CsrfMiddleware::class, UnauthMiddleware::class]]],
    ['POST', '/logout', [[AuthController::class, 'logout'], [CsrfMiddleware::class, AuthMiddleware::class]]],
    ['POST', '/folders', [[FolderController::class, 'store'], [CsrfMiddleware::class, AuthMiddleware::class]]],
    ['POST', '/folders/{id:\d+}/update', [[FolderController::class, 'update'], [CsrfMiddleware::class, AuthMiddleware::class]]],
    ['POST', '/folders/{id:\d+}/remove', [[FolderController::class, 'remove'], [CsrfMiddleware::class, AuthMiddleware::class]]],
    ['POST', '/files', [[FileController::class, 'store'], [CsrfMiddleware::class, AuthMiddleware::class]]],
    ['POST', '/files/{id:\d+}/update', [[FileController::class, 'update'], [CsrfMiddleware::class, AuthMiddleware::class]]],
    ['POST', '/files/{id:\d+}/remove', [[FileController::class, 'remove'], [CsrfMiddleware::class, AuthMiddleware::class]]],
    ['GET', '/files/{id:\d+}/download', [[FileController::class, 'download'], [CsrfMiddleware::class, AuthMiddleware::class]]],
    ['GET', '/error', [[AppController::class, 'error'], [CsrfMiddleware::class]]],
];
