<?php

use EduardoCenci\FileManager\Enum\AlertType;

$alertType = AlertType::tryFrom($_SESSION['alert_type'] ?? '') ?? AlertType::Info;
$alertMessage = isset($_SESSION['alert_message']) ? nl2br(htmlspecialchars($_SESSION['alert_message'])) : null;

unset($_SESSION['alert_message'], $_SESSION['alert_type']);
?>
<?php if ($alertMessage !== null): ?>
    <?php if ($alertType === AlertType::Success): ?>
        <div class="alert justify-center text-center bg-green-50 text-green-700 dark:bg-green-950 dark:text-green-300">
            <?= $alertMessage ?>
        </div>
    <?php elseif ($alertType === AlertType::Error): ?>
        <div class="alert justify-center text-center" data-variant="destructive">
            <?= $alertMessage ?>
        </div>
    <?php elseif ($alertType === AlertType::Info): ?>
        <div class="alert justify-center text-center">
            <?= $alertMessage ?>
        </div>
    <?php endif; ?>
<?php endif; ?>
