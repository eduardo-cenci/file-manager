<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Controller;

use EduardoCenci\FileManager\Core\Router;
use EduardoCenci\FileManager\Exception\AppException;
use EduardoCenci\FileManager\Exception\CustomException;
use EduardoCenci\FileManager\Service\AuthService;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\ValidatorBuilder as v;

final class AuthController
{
    public function __construct(
        private AuthService $authService,
        private Router $router,
    ) {}

    public function login(): void
    {
        try {
            $validator = v::key('email', v::named('E-mail', v::email()))
                ->key('password', v::named('Password', v::not(v::undef())));

            $validator->assert($_POST);

            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = filter_input(INPUT_POST, 'password');

            $this->authService->login($email, $password);

            $this->router->redirect('/');
        } catch (ValidationException $exception) {
            throw new AppException($exception->getMessages(), '/login');
        } catch (CustomException $exception) {
            throw new AppException([$exception->getMessage()], '/login');
        }
    }

    public function logout(): void
    {
        $this->authService->logout();

        $this->router->redirect('/login');
    }
}
