<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Service;

use EduardoCenci\FileManager\Exception\CustomException;
use EduardoCenci\FileManager\Model\User;
use EduardoCenci\FileManager\Repository\UserRepository;
use PDOException;

final class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private FolderService $folderService,
    ) {}

    public function register(
        string $name,
        string $email,
        string $password,
    ): User {
        $user = new User($name, $email);
        $user->password = password_hash($password, PASSWORD_ARGON2ID);

        try {
            $this->userRepository->add($user);
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                throw new CustomException('This email is already registered');
            }
            throw $exception;
        }

        $this->folderService->storeRoot($user->id);

        return $user;
    }
}
