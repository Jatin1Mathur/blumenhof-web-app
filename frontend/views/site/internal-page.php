<?php
declare(strict_types=1);

use yii\helpers\Html;

$this->title = Html::encode($moduleName);
?>

<div class="container-fluid px-4 py-5">
    <div class="about-title-bar">
        <div class="container-fluid px-4">
            <div class="about-title-grid">
                <div></div>
                <div class="about-title-center">INTERNAL MODULE: <?= Html::encode($moduleName) ?></div>
                <div></div>
            </div>
        </div>
    </div>

    <?php if ($canView || $canManage): ?>
        <div class="about-wide-card mt-5">
            <h2><?= Html::encode($moduleName) ?> preview</h2>
            <p>
                This page shows the internal <?= Html::encode(strtolower($moduleName)) ?> preview for permitted users.
                The page is visible when the current user has the correct view or manage permission for this area.
            </p>

            <div class="bh-action-badge-group">
                <?php if ($canView): ?>
                    <span class="bh-view-only-badge">View</span>
                <?php endif; ?>
                <?php if ($canManage): ?>
                    <span class="bh-manage-badge">Manage</span>
                <?php endif; ?>
            </div>

            <div class="bh-action-buttons">
                <?php if ($canView): ?>
                    <?= Html::a('Open ' . Html::encode($moduleName), ['/site/' . $routeId], ['class' => 'about-main-btn']) ?>
                <?php endif; ?>
                <?php if ($canManage): ?>
                    <?= Html::a('Manage ' . Html::encode($moduleName), ['/site/' . $routeId . '-manage'], ['class' => 'about-outline-btn']) ?>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="bh-access-denied-card">
            <h2>Access denied</h2>
            <p>
                You do not have permission to view the <?= Html::encode(strtolower($moduleName)) ?> preview.
                Please contact your administrator if you believe you should have access.
            </p>
        </div>
    <?php endif; ?>
</div>
