<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Controller;

use EduardoCenci\FileManager\Core\Router;
use EduardoCenci\FileManager\Enum\AlertType;
use EduardoCenci\FileManager\Exception\AppException;
use EduardoCenci\FileManager\Exception\CustomException;
use EduardoCenci\FileManager\Service\FolderService;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\ValidatorBuilder as v;

final class FolderController
{
    public function __construct(
        private FolderService $folderService,
        private Router $router,
    ) {}

    public function store(): void
    {
        try {
            $_POST['parent_id'] = (int) $_POST['parent_id'];

            $validator = v::key('name', v::named('Folder Name', v::length(v::between(1, 100))))
                ->key('parent_id', v::named('Parent Folder', v::intType()));

            $validator->assert($_POST);

            $name = filter_input(INPUT_POST, 'name');
            $parentId = (int) filter_input(INPUT_POST, 'parent_id', FILTER_SANITIZE_NUMBER_INT);

            $folder = $this->folderService->store($name, $parentId);

            $this->router->redirect("/?folder_id={$folder->parentId}", 'Folder successfully created', AlertType::Success);
        } catch (ValidationException $exception) {
            throw new AppException($exception->getMessages(), '/');
        } catch (CustomException $exception) {
            throw new AppException([$exception->getMessage()], '/');
        }
    }

    public function update(string $id): void
    {
        try {
            $validator = v::key('name', v::named('Folder Name', v::length(v::between(1, 100))));

            $validator->assert($_POST);

            $name = filter_input(INPUT_POST, 'name');

            $folder = $this->folderService->update((int) $id, $name);

            $this->router->redirect("/?folder_id={$folder->parentId}", 'Folder successfully updated', AlertType::Success);
        } catch (ValidationException $exception) {
            throw new AppException($exception->getMessages(), '/');
        } catch (CustomException $exception) {
            throw new AppException([$exception->getMessage()], '/');
        }
    }

    public function remove(string $id): void
    {
        try {
            $folder = $this->folderService->remove((int) $id);

            $this->router->redirect("/?folder_id={$folder->parentId}", 'Folder successfully removed', AlertType::Success);
        } catch (CustomException $exception) {
            throw new AppException([$exception->getMessage()], '/');
        }
    }
}
