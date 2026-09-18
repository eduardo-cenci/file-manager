<header class="flex justify-between gap-4 p-4 border-b">
    <h1>
        <a class="text-base font-semibold" href="/">
            File Manager
        </a>
    </h1>
    <div class="dropdown-menu">
        <button class="btn" data-variant="ghost" data-size="icon" type="button">
            <?= $this->fetchIcon('options') ?>
        </button>
        <div data-popover class="min-w-32 left-auto right-0">
            <div role="menu">
                <form method="post" action="/logout">
                    <?= $this->fetch('partials/csrf-field') ?>
                    <button type="submit" role="menuitem">
                        <?= $this->fetchIcon('logout') ?>
                        Log out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>