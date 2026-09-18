<?php

/** @var string $title */

?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= htmlspecialchars($title)?>
    </title>
    <script src="assets/tailwindcss-browser@4.min.js"></script>
    <script src="assets/basecoat-css@1.0.2.min.js" defer></script>
    <link rel="stylesheet" href="assets/basecoat-css@1.0.2-mira.min.css" />
</head>
<body>
    <?=$this->section('content')?>
</body>
</html>