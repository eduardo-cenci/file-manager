<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Controller;

use EduardoCenci\FileManager\Core\Router;
use EduardoCenci\FileManager\Enum\AlertType;
use EduardoCenci\FileManager\Exception\AppException;
use EduardoCenci\FileManager\Exception\CustomException;
use EduardoCenci\FileManager\Service\UserService;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\ValidatorBuilder as v;

final class UserController
{
    public function __construct(
        private UserService $userService,
        private Router $router,
    ) {}

    public function register(): void
    {
        try {
            $validator = v::key('name', v::named('Name', v::length(v::between(1, 100))))
                ->key('email', v::named('E-mail', v::email()))
                ->key('password', v::named('Password', v::length(v::between(10, 100))));

            $validator->assert($_POST);

            $name = filter_input(INPUT_POST, 'name');
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = filter_input(INPUT_POST, 'password');

            $this->userService->register($name, $email, $password);

            $this->router->redirect('/login', 'Registration successfully completed', AlertType::Success);
        } catch (ValidationException $exception) {
            throw new AppException($exception->getMessages(), '/register');
        } catch (CustomException $exception) {
            throw new AppException([$exception->getMessage()], '/register');
        }
    }
}
