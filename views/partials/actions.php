<div class="flex flex-wrap gap-2">
    <button class="btn" type="button" data-variant="outline" onclick="
        folder_modal_title.innerHTML = 'Create folder';
        folder_form.action = '/folders';
        folder_name_field.value = '';
        folder_modal.showModal();
    ">
        <?= $this->fetchIcon('plus') ?>
        Create Folder
    </button>
    <button class="btn" type="button" onclick="upload_modal.showModal()">
        <?= $this->fetchIcon('upload') ?>
        Upload File
    </button>
</div>