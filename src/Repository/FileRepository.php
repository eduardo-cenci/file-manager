<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Repository;

use DateTimeImmutable;
use EduardoCenci\FileManager\Model\File;
use PDO;

final class FileRepository
{
    public function __construct(private PDO $pdo) {}

    public function add(File $file): bool
    {
        $statement = $this->pdo->prepare('INSERT INTO files (user_id, folder_id, name, extension, storage_name, mime_type, size)
            VALUES (:user_id, :folder_id, :name, :extension, :storage_name, :mime_type, :size);');
        $statement->bindValue(':user_id', $file->userId, PDO::PARAM_INT);
        $statement->bindValue(':folder_id', $file->folderId, $file->folderId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $statement->bindValue(':name', $file->name, PDO::PARAM_STR);
        $statement->bindValue(':extension', $file->extension, PDO::PARAM_STR);
        $statement->bindValue(':storage_name', $file->storageName, PDO::PARAM_STR);
        $statement->bindValue(':mime_type', $file->mimeType, PDO::PARAM_STR);
        $statement->bindValue(':size', $file->size, PDO::PARAM_INT);

        $result = $statement->execute();

        $file->id = (int) $this->pdo->lastInsertId();

        return $result;
    }

    public function findAll(
        int $userId,
        ?int $folderId = null,
    ): array {
        $statement = $this->pdo->prepare('SELECT * FROM files WHERE user_id = :user_id AND folder_id = :folder_id;');
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $statement->bindValue(':folder_id', $folderId, $folderId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $statement->execute();

        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn(array $fileData) => $this->createObject($fileData), $data);
    }

    public function findAllInFolderTree(
        int $userId,
        int $folderId,
    ): array {
        $statement = $this->pdo->prepare(
            'WITH RECURSIVE folder_tree AS (
                SELECT id
                FROM folders
                WHERE id = :folder_id AND user_id = :user_id
                UNION ALL
                SELECT folders.id
                FROM folders
                INNER JOIN folder_tree ON folders.parent_id = folder_tree.id
                WHERE folders.user_id = :user_id
            )
            SELECT files.*
            FROM files
            INNER JOIN folder_tree ON files.folder_id = folder_tree.id;'
        );
        $statement->bindValue(':folder_id', $folderId, PDO::PARAM_INT);
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $statement->execute();

        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn(array $fileData) => $this->createObject($fileData), $data);
    }

    public function findByStorageNames(array $storageNames): array
    {
        $placeholders = implode(',', array_fill(0, count($storageNames), '?'));

        $statement = $this->pdo->prepare("SELECT storage_name FROM files WHERE storage_name IN ($placeholders)");
        $statement->execute($storageNames);

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    public function find(
        int $id,
        int $userId,
    ): ?File {
        $statement = $this->pdo->prepare('SELECT * FROM files WHERE id = :id AND user_id = :user_id;');
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $statement->execute();

        $data = $statement->fetch(PDO::FETCH_ASSOC);

        return $data === false ? null : $this->createObject($data);
    }

    public function update(File $file): bool
    {
        $statement = $this->pdo->prepare('UPDATE files SET name = :name WHERE id = :id;');
        $statement->bindValue(':id', $file->id, PDO::PARAM_INT);
        $statement->bindValue(':name', $file->name, PDO::PARAM_STR);

        return $statement->execute();
    }

    public function remove(int $id): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM files WHERE id = :id');
        $statement->bindValue(':id', $id);

        return $statement->execute();
    }

    private function createObject(array $data): File
    {
        $file = new File($data['name']);

        $file->id = $data['id'];
        $file->userId = $data['user_id'];
        $file->folderId = $data['folder_id'];
        $file->size = $data['size'];
        $file->extension = $data['extension'];
        $file->storageName = $data['storage_name'];
        $file->mimeType = $data['mime_type'];
        $file->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['created_at']);
        $file->updatedAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['updated_at']);

        return $file;
    }
}
