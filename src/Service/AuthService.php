<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Service;

use EduardoCenci\FileManager\Core\Session;
use EduardoCenci\FileManager\Exception\CustomException;
use EduardoCenci\FileManager\Repository\UserRepository;

final class AuthService
{
    public function __construct(private UserRepository $userRepository) {}

    public function login(
        string $email,
        string $password,
    ): void {
        $user = $this->userRepository->find($email);

        if ($user === null || !password_verify($password, $user->password)) {
            throw new CustomException('Incorrect email or password');
        }

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user->id;
    }

    public function logout(): void
    {
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly'],
            );
        }

        Session::destroy();
    }
}
