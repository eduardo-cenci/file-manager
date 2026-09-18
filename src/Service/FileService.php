<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Service;

use EduardoCenci\FileManager\Exception\CustomException;
use EduardoCenci\FileManager\Model\File;
use EduardoCenci\FileManager\Repository\FileRepository;
use EduardoCenci\FileManager\Repository\FolderRepository;
use finfo;

final class FileService
{
    public function __construct(
        private FileRepository $fileRepository,
        private FolderRepository $folderRepository,
    ) {}

    public function store(
        array $fileData,
        int $folderId,
    ): File {
        $userId = $_SESSION['user_id'];
        $folder = $this->folderRepository->find($folderId, $userId);

        if ($folder === null) {
            throw new CustomException('Invalid folder');
        }

        switch ($fileData['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new CustomException('The uploaded file exceeds the maximum allowed size');
            case UPLOAD_ERR_NO_FILE:
                throw new CustomException('No file was uploaded');
            default:
                throw new CustomException('Failed to upload the file');
        }

        $fileName = pathinfo($fileData['name'], PATHINFO_BASENAME);
        $fileTmpName = $fileData['tmp_name'];

        $file = new File($fileName);
        $file->userId = $userId;
        $file->folderId = $folderId;
        $file->size = $fileData['size'];
        $file->extension = pathinfo($fileData['name'], PATHINFO_EXTENSION);
        $file->storageName = bin2hex(random_bytes(16)) . '.' . $file->extension;
        $file->mimeType = (new finfo(FILEINFO_MIME_TYPE))->file($fileTmpName);

        $filePath = $this->getFilePath($file->storageName);

        if (!is_uploaded_file($fileTmpName) || !move_uploaded_file($fileTmpName, $filePath)) {
            throw new CustomException('Failed to upload the file');
        }

        $this->fileRepository->add($file);

        return $file;
    }

    public function findAll(?int $folderId): array
    {
        return $this->fileRepository->findAll($_SESSION['user_id'], $folderId);
    }

    public function findAllInFolderTree(int $folderId): array
    {
        return $this->fileRepository->findAllInFolderTree(
            $_SESSION['user_id'],
            $folderId,
        );
    }

    public function findAndValidate(int $id): File
    {
        $file = $this->fileRepository->find($id, $_SESSION['user_id']);

        if ($file === null) {
            throw new CustomException('File not found');
        }

        return $file;
    }

    public function update(
        int $id,
        string $name,
    ): File {
        $file = $this->findAndValidate($id);
        $file->name = $name;

        $this->fileRepository->update($file);

        return $file;
    }

    public function remove(int $id): File
    {
        $file = $this->findAndValidate($id);
        
        $this->fileRepository->remove($id);
        $this->removePhysicalFile($file);

        return $file;
    }

    public function removePhysicalFile(File $file): void
    {
        $filePath = $this->getFilePath($file->storageName);

        if (is_file($filePath)) {
            unlink($filePath);
        }
    }

    private function getFilePath(string $fileName): string
    {
        return __DIR__ . '/../../storage/files/' . $fileName;
    }
}
