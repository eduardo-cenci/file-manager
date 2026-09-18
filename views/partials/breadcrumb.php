<?php

/** @var array $breadcrumb */

?>
<nav class="breadcrumb">
    <ol>
        <?php foreach ($breadcrumb as $i => $folder): ?>
            <li>
                <?php if ($i < count($breadcrumb) - 1): ?>
                    <a href="/?folder_id=<?= $folder->id; ?>">
                        <?= htmlspecialchars($folder->name); ?>
                    </a>
                <?php else: ?>
                    <span aria-current="page">
                        <?= htmlspecialchars($folder->name); ?>
                    </span>
                <?php endif; ?>
            </li>
            <?php if ($i < count($breadcrumb) - 1): ?>
                <li>
                    <span class="inline-block w-3 aspect-square">
                        <?= $this->fetchIcon('chevron-right') ?>
                    </span>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ol>
</nav>