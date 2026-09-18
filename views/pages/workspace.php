<?php

use EduardoCenci\FileManager\Model\Folder;

/** @var Folder $selectedFolder */
/** @var array $breadcrumb */
/** @var array $subFolders */
/** @var array $files */

?>
<?php $this->layout('main', ['title' => 'File Manager | ' . htmlspecialchars($selectedFolder->name)]) ?>
<main>
    <?= $this->fetch('partials/header') ?>
    <main class="grid gap-4 p-4">
        <?= $this->fetch('partials/alert') ?>
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <?= $this->fetch('partials/breadcrumb', compact('breadcrumb')) ?>
            <?= $this->fetch('partials/actions') ?>
        </div>
        <?php if (empty($subFolders) && empty($files)): ?>
            <?= $this->fetch('partials/empty') ?>
        <?php else: ?>
            <div class="overflow-hidden rounded-md border">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Creation</th>
                                <th>Last Update</th>
                                <th class="w-[18rem]"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subFolders as $folder): ?>
                                <tr>
                                    <td><?= $folder->id; ?></td>
                                    <td>
                                        <?= htmlspecialchars($folder->name); ?></td>
                                    <td>
                                        <span class="badge bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300">
                                            <span class="inline-block w-3 aspect-square">
                                                <?= $this->fetchIcon('folder') ?>
                                            </span>
                                            folder
                                        </span>
                                    </td>
                                    <td>-</td>
                                    <td><?= $folder->createdAt->format('d/m/Y H:i'); ?></td>
                                    <td><?= $folder->updatedAt->format('d/m/Y H:i'); ?></td>
                                    <td class="text-end">
                                        <div class="inline-flex gap-2">
                                            <a class="btn" data-size="sm" data-variant="outline" href="/?folder_id=<?= $folder->id; ?>">
                                                <?= $this->fetchIcon('inside') ?>
                                                Open
                                            </a>
                                            <button type="button" class="btn" data-variant="secondary" data-size="sm" onclick="
                                                folder_modal_title.innerHTML = 'Edit folder';
                                                folder_form.action = '/folders/<?= $folder->id; ?>/update';
                                                folder_name_field.value = '<?= htmlspecialchars($folder->name); ?>';
                                                folder_modal.showModal();
                                            ">
                                                <?= $this->fetchIcon('edit') ?>
                                                Edit
                                            </button>
                                            <form 
                                                method="post"
                                                action="/folders/<?= $folder->id; ?>/remove"
                                                onsubmit="return confirm('Are you sure you want to remove this folder and its contents?');">
                                                <?= $this->fetch('partials/csrf-field') ?>
                                                <button type="submit" class="btn" data-variant="destructive" data-size="sm">
                                                    <?= $this->fetchIcon('trash') ?>
                                                    Remove
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php foreach ($files as $file): ?>
                                <tr>
                                    <td><?= $file->id; ?></td>
                                    <td><?= htmlspecialchars($file->name); ?></td>
                                    <td>
                                        <span class="badge bg-green-50 text-green-700 dark:bg-green-950 dark:text-green-300">
                                            <span class="inline-block w-3 aspect-square">
                                                <?= $this->fetchIcon('file') ?>
                                            </span>
                                            <?= $file->mimeType; ?>
                                        </span>
                                    </td>
                                    <td><?= $this->readableBytes($file->size) ?></td>
                                    <td><?= $file->createdAt->format('d/m/Y H:i'); ?></td>
                                    <td><?= $file->updatedAt->format('d/m/Y H:i'); ?></td>
                                    <td class="text-end">
                                        <div class="inline-flex gap-2">
                                            <a class="btn" data-size="sm" data-variant="outline" href="/files/<?= $file->id; ?>/download">
                                                <?= $this->fetchIcon('download') ?>
                                                Download
                                            </a>
                                            <button type="button" class="btn" data-variant="secondary" data-size="sm" onclick="
                                                file_modal_title.innerHTML = 'Edit file';
                                                file_form.action = '/files/<?= $file->id; ?>/update';
                                                file_name_field.value = '<?= htmlspecialchars($file->name); ?>';
                                                file_modal.showModal();
                                            ">
                                                <?= $this->fetchIcon('edit') ?>
                                                Edit
                                            </button>
                                            <form
                                                method="post"
                                                action="/files/<?= $file->id; ?>/remove"
                                                onsubmit="return confirm('Are you sure you want to delete this file?');">
                                                <?= $this->fetch('partials/csrf-field') ?>
                                                <button type="submit" class="btn" data-variant="destructive" data-size="sm">
                                                    <?= $this->fetchIcon('trash') ?>
                                                    Remove
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </main>
    <?= $this->fetch('partials/folder-form-modal', compact('selectedFolder')) ?>
    <?= $this->fetch('partials/file-upload-modal', compact('selectedFolder')) ?>
    <?= $this->fetch('partials/file-form-modal') ?>
</main>