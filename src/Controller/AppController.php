<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Controller;

use EduardoCenci\FileManager\Exception\HttpException;
use EduardoCenci\FileManager\Model\Folder;
use EduardoCenci\FileManager\Service\FileService;
use EduardoCenci\FileManager\Service\FolderService;
use League\Plates\Engine;

final class AppController
{
    public function __construct(
        private FileService $fileService,
        private FolderService $folderService,
        private Engine $templateEngine,
    ) {}

    public function register(): void
    {
        $this->renderPage('register');
    }

    public function login(): void
    {
        $this->renderPage('login');
    }

    public function workspace(): void
    {
        $folder_id = filter_input(INPUT_GET, 'folder_id', FILTER_VALIDATE_INT);

        if ($folder_id === false) {
            throw new HttpException(404, 'Not found');
        }

        if ($folder_id === null) {
            $rootFolder = $this->folderService->findRoot();

            if ($rootFolder === null) {
                throw new HttpException(404, 'Not found');
            }

            $folder_id = $rootFolder->id;
        }

        $folders = $this->folderService->findAll();
        $selectedFolder = array_find($folders, fn(Folder $folder) => $folder->id === (int) $folder_id);

        if ($selectedFolder === null) {
            throw new HttpException(404, 'Not found');
        }

        $breadcrumb = $this->folderService->breadcrumb($selectedFolder, $folders);
        $subFolders = array_values(array_filter($folders, fn(Folder $folder) => $folder->parentId === $selectedFolder->id));
        $files = $this->fileService->findAll($selectedFolder->id);

        $this->renderPage('workspace', compact('selectedFolder', 'breadcrumb', 'subFolders', 'files'));
    }

    public function error(): void
    {
        $this->renderPage('error');
    }

    private function renderPage(
        string $view,
        array $data = [],
    ): void {
        echo $this->templateEngine->render('pages/' . $view, $data);

        exit;
    }
}
