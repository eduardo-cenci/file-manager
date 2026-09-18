<?php $this->layout('main', ['title' => 'File Manager | Login']) ?>
<main class="min-h-dvh flex flex-col items-center justify-center p-4">
    <div class="card w-full max-w-xs p-4 text-center">
        <h1 class="text-base font-semibold">File Manager - Login</h1>
        <?= $this->fetch('partials/alert') ?>
        <form class="grid gap-4" method="post" action="/login">
            <?= $this->fetch('partials/csrf-field') ?>
            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" required />
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required />
            </div>
            <button class="btn" type="submit">Login</button>
        </form>
        <p>
            Don't have an account? <a class="hover:underline" href="/register">Register</a>
        </p>
    </div>
</main>