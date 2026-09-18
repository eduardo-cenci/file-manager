<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Repository;

use DateTimeImmutable;
use PDO;
use EduardoCenci\FileManager\Model\Folder;

final class FolderRepository
{
    public function __construct(private PDO $pdo) {}

    public function add(Folder $folder): bool
    {
        $statement = $this->pdo->prepare('INSERT INTO folders (user_id, parent_id, name) VALUES (:user_id, :parent_id, :name);');
        $statement->bindValue(':user_id', $folder->userId, PDO::PARAM_INT);
        $statement->bindValue(':parent_id', $folder->parentId, $folder->parentId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $statement->bindValue(':name', $folder->name, PDO::PARAM_STR);

        $result = $statement->execute();

        $folder->id = (int) $this->pdo->lastInsertId();

        return $result;
    }

    public function find(
        int $id,
        int $userId,
    ): ?Folder {
        $statement = $this->pdo->prepare('SELECT * FROM folders WHERE id = :id AND user_id = :user_id;');
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $statement->execute();

        $data = $statement->fetch(PDO::FETCH_ASSOC);

        return $data === false ? null : $this->createObject($data);
    }

    public function findRoot(int $userId): ?Folder
    {
        $statement = $this->pdo->prepare('SELECT * FROM folders WHERE user_id = :user_id AND parent_id IS NULL');
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $statement->execute();

        $data = $statement->fetch(PDO::FETCH_ASSOC);

        return $data === false ? null : $this->createObject($data);
    }

    public function findAll(int $userId): array
    {
        $statement = $this->pdo->prepare('SELECT * FROM folders WHERE user_id = :user_id;');
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $statement->execute();

        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn(array $folderData) => $this->createObject($folderData), $data);
    }

    public function update(Folder $folder): bool
    {
        $statement = $this->pdo->prepare('UPDATE folders SET name = :name WHERE id = :id;');
        $statement->bindValue(':id', $folder->id, PDO::PARAM_INT);
        $statement->bindValue(':name', $folder->name, PDO::PARAM_STR);

        return $statement->execute();
    }

    public function remove(int $id): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM folders WHERE id = :id');
        $statement->bindValue(':id', $id);

        return $statement->execute();
    }

    private function createObject(array $data): Folder
    {
        $folder = new Folder($data['name']);

        $folder->id = $data['id'];
        $folder->userId = $data['user_id'];
        $folder->parentId = $data['parent_id'];
        $folder->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['created_at']);
        $folder->updatedAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['updated_at']);

        return $folder;
    }
}
