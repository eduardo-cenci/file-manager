<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Repository;

use DateTimeImmutable;
use PDO;
use EduardoCenci\FileManager\Model\User;

final class UserRepository
{
    public function __construct(private PDO $pdo) {}

    public function add(User $user): bool
    {
        $statement = $this->pdo->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $statement->bindValue(':name', $user->name, PDO::PARAM_STR);
        $statement->bindValue(':email', $user->email, PDO::PARAM_STR);
        $statement->bindValue(':password', $user->password, PDO::PARAM_STR);

        $result = $statement->execute();

        $user->id = (int) $this->pdo->lastInsertId();

        return $result;
    }

    public function find(string $email): ?User
    {
        $statement = $this->pdo->prepare('SELECT * FROM users WHERE email = :email;');
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->execute();

        $data = $statement->fetch(PDO::FETCH_ASSOC);

        return $data === false ? null : $this->createObject($data);
    }

    private function createObject(array $data): User
    {
        $user = new User($data['name'], $data['email']);

        $user->id = $data['id'];
        $user->password = $data['password'];
        $user->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['created_at']);
        $user->updatedAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['updated_at']);

        return $user;
    }
}
