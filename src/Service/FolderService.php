<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Service;

use EduardoCenci\FileManager\Exception\CustomException;
use EduardoCenci\FileManager\Model\Folder;
use EduardoCenci\FileManager\Repository\FolderRepository;
use PDOException;

final class FolderService
{
    public function __construct(
        private FolderRepository $folderRepository,
        private FileService $fileService,    
    ) {}

    public function store(
        string $name,
        int $parentId,
    ): Folder {
        $folder = new Folder($name);
        $folder->parentId = $parentId;
        $folder->userId = $_SESSION['user_id'];

        $parentFolder = $this->folderRepository->find(
            $folder->parentId,
            $folder->userId,
        );

        if ($parentFolder === null) {
            throw new CustomException('Invalid parent folder');
        }

        try {
            $this->folderRepository->add($folder);
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                throw new CustomException('This folder already exists');
            }
            throw $exception;
        }

        return $folder;
    }

    public function storeRoot(int $userId): Folder
    {
        $folder = new Folder('root');
        $folder->userId = $userId;

        $this->folderRepository->add($folder);

        return $folder;
    }

    public function findAll(): array
    {
        return $this->folderRepository->findAll($_SESSION['user_id']);
    }

    public function findRoot(): ?Folder
    {
        return $this->folderRepository->findRoot($_SESSION['user_id']);
    }

    public function update(
        int $id,
        string $name,
    ): Folder {
        $folder = $this->folderRepository->find($id, $_SESSION['user_id']);

        if ($folder === null || $folder->isRoot()) {
            throw new CustomException('Invalid folder');
        }

        $folder->name = $name;

        try {
            $this->folderRepository->update($folder);
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                throw new CustomException('This folder already exists');
            }
            throw $exception;
        }

        return $folder;
    }

    public function remove(int $id): Folder
    {
        $folder = $this->folderRepository->find((int) $id, $_SESSION['user_id']);

        if ($folder === null || $folder->isRoot()) {
            throw new CustomException('Invalid folder');
        }

        $files = $this->fileService->findAllInFolderTree($folder->id);

        $this->folderRepository->remove($folder->id);

        foreach ($files as $file) {
            $this->fileService->removePhysicalFile($file);
        }

        return $folder;
    }

    public function breadcrumb(
        Folder $folder,
        array $folders,
    ): array {
        $breadcrumb = [];
        $currentFolder = $folder;

        do {
            $breadcrumb[] = $currentFolder;
            $currentFolder = array_find($folders, fn(Folder $item) => $item->id === $currentFolder->parentId);
        } while ($currentFolder !== null);

        return array_reverse($breadcrumb);
    }
}
