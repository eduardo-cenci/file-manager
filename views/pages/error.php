<?php $this->layout('main', ['title' => 'File Manager | Error']) ?>
<main class="min-h-dvh flex flex-col items-center justify-center p-4">
    <div class="card w-full max-w-xs p-4 text-center">
        <h1 class="text-base font-semibold">File Manager - Error</h1>
        <p class="alert justify-center text-center" data-variant="destructive">
            <?= nl2br(htmlspecialchars($errorMessage ?? 'Something went wrong.')); ?>
        </p>
        <a class="hover:underline" href="/">Back to home</a>
    </div>
</main>