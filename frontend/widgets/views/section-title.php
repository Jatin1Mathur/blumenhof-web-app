<?php
/** @var string $title */
/** @var string $subtitle */
/** @var string $align */
?>
<div class="mb-4">
    <p class="about-small-heading mb-3 <?= $align === 'left' ? 'text-start' : 'text-center' ?>"><?= htmlspecialchars($subtitle, ENT_QUOTES, 'UTF-8') ?></p>
    <h2 class="fw-semibold mb-0 <?= $align === 'left' ? 'text-start' : 'text-center' ?>"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h2>
</div>
