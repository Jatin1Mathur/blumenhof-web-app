<?php

declare(strict_types=1);

use yii\helpers\Html;

$user = Yii::$app->user;
$isGuest = $user->isGuest;

// Each check uses the real RBAC permission, matching the access matrix
// exactly. "view*" already covers anyone with "manage*" too, since manage
// is defined as a parent of view in RbacController.php.
$canCrm = !$isGuest && $user->can('viewCrm');
$canCatalog = !$isGuest && $user->can('viewCatalog');
$canOrders = !$isGuest && $user->can('viewOrders');
$canProduction = !$isGuest && $user->can('viewProduction');
$canDashboard = !$isGuest && $user->can('viewDashboard');
$canManageUsers = !$isGuest && $user->can('manageUsers');
?>

<div id="sidebar-overlay" class="sidebar-overlay"></div>

<aside id="app-sidebar" class="app-sidebar" aria-hidden="true">
    <div class="sidebar-header">
        <div>
            <div class="sidebar-title">blumenHof Menu</div>
            <div class="sidebar-subtitle">Internal florist system</div>
        </div>
        <button id="sidebar-close" class="sidebar-close-btn" aria-label="Close menu">&times;</button>
    </div>

    <?php if ($canDashboard || $canCrm || $canCatalog || $canOrders || $canProduction): ?>
        <div class="sidebar-section-label">Shop Management</div>
        <nav class="sidebar-nav">
            <?php if ($canCrm): ?>
                <?= Html::a('👥 Customers / CRM', ['/customer-company/index']) ?>
            <?php endif; ?>
            <?php if ($canCatalog): ?>
                <?= Html::a('💐 Catalog &amp; Stock', ['/product/index']) ?>
            <?php endif; ?>
            <?php if ($canOrders): ?>
                <?= Html::a('🧾 Orders', ['/order/index']) ?>
            <?php endif; ?>
            <?php if ($canProduction): ?>
                <?= Html::a('🏭 Production', ['/production-order/index']) ?>
            <?php endif; ?>
            <?php if ($canCatalog): ?>
                <?= Html::a('📦 Inventory', ['/inventory-stock/index']) ?>
            <?php endif; ?>
            <?php if ($canDashboard): ?>
                <?= Html::a('📊 Dashboard', ['/dashboard/index']) ?>
            <?php endif; ?>
        </nav>
    <?php endif; ?>

    <?php if ($canManageUsers): ?>
        <div class="sidebar-section-label">Administration</div>
        <nav class="sidebar-nav">
            <?= Html::a('🔑 User Management', ['/user/index']) ?>
        </nav>
    <?php endif; ?>

</aside>
