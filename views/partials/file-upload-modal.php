<?php

use EduardoCenci\FileManager\Model\Folder;

/** @var Folder $selectedFolder */

?>
<dialog class="dialog" id="upload_modal" onclick="if (event.target === this) this.close()">
    <div class="max-w-sm">
        <header>
            <h2>Upload file</h2>
        </header>
        <section>
            <form class="grid gap-4" id="upload_form" method="post" action="/files" enctype="multipart/form-data">
                <?= $this->fetch('partials/csrf-field') ?>
                <input type="hidden" name="folder_id" value="<?= $selectedFolder->id;  ?>">
                <div class="field">
                    <label for="file">File</label>
                    <input id="file" type="file" name="file" required />
                </div>
                <button class="hidden" id="upload_btn" type="submit">Submit</button>
            </form>
        </section>
        <footer>
            <button class="btn" type="button" data-variant="outline" onclick="upload_modal.close()">Cancel</button>
            <label class="btn" for="upload_btn">Submit</label>
        </footer>
    </div>
</dialog>