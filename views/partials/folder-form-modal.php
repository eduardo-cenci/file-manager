<?php

use EduardoCenci\FileManager\Model\Folder;

/** @var Folder $selectedFolder */

?>
<dialog class="dialog" id="folder_modal" onclick="if (event.target === this) this.close()">
    <div class="max-w-sm">
        <header>
            <h2 id="folder_modal_title">Folder</h2>
        </header>
        <section>
            <form class="grid gap-4" id="folder_form" method="post" action="/folders">
                <?= $this->fetch('partials/csrf-field') ?>
                <input type="hidden" name="parent_id" value="<?= $selectedFolder->id;  ?>">
                <div class="field">
                    <label for="folder_name_field">Name</label>
                    <input id="folder_name_field" type="text" name="name" required />
                </div>
                <button class="hidden" id="folder_form_btn" type="submit">Submit</button>
            </form>
        </section>
        <footer>
            <button class="btn" type="button" data-variant="outline" onclick="folder_modal.close()">Cancel</button>
            <label class="btn" for="folder_form_btn">Submit</label>
        </footer>
    </div>
</dialog>