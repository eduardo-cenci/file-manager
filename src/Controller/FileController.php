<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Controller;

use EduardoCenci\FileManager\Core\Router;
use EduardoCenci\FileManager\Enum\AlertType;
use EduardoCenci\FileManager\Exception\AppException;
use EduardoCenci\FileManager\Exception\CustomException;
use EduardoCenci\FileManager\Service\FileService;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\ValidatorBuilder as v;

final class FileController
{
    public function __construct(
        private FileService $fileService,
        private Router $router,
    ) {}

    public function store(): void
    {
        try {
            $_POST['folder_id'] = (int) $_POST['folder_id'];

            $validator = v::key('folder_id', v::named('Folder', v::intType()));

            $validator->assert($_POST);

            $folderId = (int) filter_input(INPUT_POST, 'folder_id', FILTER_SANITIZE_NUMBER_INT);
            $fileData = $_FILES['file'];

            $file = $this->fileService->store($fileData, $folderId);

            $this->router->redirect("/?folder_id={$file->folderId}", 'File uploaded successfully', AlertType::Success);
        } catch (ValidationException $exception) {
            throw new AppException($exception->getMessages(), '/');
        } catch (CustomException $exception) {
            throw new AppException([$exception->getMessage()], '/');
        }
    }

    public function download(string $id): void
    {
        try {
            $file = $this->fileService->findAndValidate((int) $id);

            $filePath = __DIR__ . '/../../storage/files/' . $file->storageName;
            $fileName = preg_replace('/[\x00-\x1F\x7F]/', '', $file->name) ?? 'file';
            $fileName = $fileName === '' ? 'file' : rawurlencode($fileName);

            if (!is_file($filePath) || !is_readable($filePath)) {
                throw new CustomException('File not found');
            }

            header('Content-type: application/octet-stream');
            header("Content-Disposition: attachment; filename*=UTF-8''" . $fileName);
            header('Content-Length: ' . $file->size);

            readfile($filePath);
        } catch (CustomException $exception) {
            throw new AppException([$exception->getMessage()], '/');
        }
    }

    public function update(string $id): void
    {
        try {
            $validator = v::key('name', v::named('Folder Name', v::length(v::between(1, 100))));

            $validator->assert($_POST);

            $name = pathinfo(filter_input(INPUT_POST, 'name'), PATHINFO_BASENAME);

            $file = $this->fileService->update((int) $id, $name);

            $this->router->redirect("/?folder_id={$file->folderId}", 'File successfully updated', AlertType::Success);
        } catch (ValidationException $exception) {
            throw new AppException($exception->getMessages(), '/');
        }
    }

    public function remove(string $id): void
    {
        try {
            $file = $this->fileService->remove((int) $id);

            $this->router->redirect("/?folder_id={$file->folderId}", 'File successfully removed', AlertType::Success);
        } catch (CustomException $exception) {
            throw new AppException([$exception->getMessage()], '/');
        }
    }
}
