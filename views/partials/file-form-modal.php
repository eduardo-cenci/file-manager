<dialog class="dialog" id="file_modal" onclick="if (event.target === this) this.close()">
    <div class="max-w-sm">
        <header>
            <h2 id="file_modal_title">Folder</h2>
        </header>
        <section>
            <form class="grid gap-4" id="file_form" method="post" action="/files">
                <?= $this->fetch('partials/csrf-field') ?>
                <div class="field">
                    <label for="file_name_field">Name</label>
                    <input id="file_name_field" type="text" name="name" required />
                </div>
                <button class="hidden" id="file_form_btn" type="submit">Submit</button>
            </form>
        </section>
        <footer>
            <button class="btn" type="button" data-variant="outline" onclick="file_modal.close()">Cancel</button>
            <label class="btn" for="file_form_btn">Submit</label>
        </footer>
    </div>
</dialog>