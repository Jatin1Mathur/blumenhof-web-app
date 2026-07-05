<?php

declare(strict_types=1);

use yii\helpers\Html;

$user = Yii::$app->user;
$isGuest = $user->isGuest;

$canCrm = !$isGuest && ($user->can('salesEmployee') || $user->can('manager') || $user->can('owner') || $user->can('admin'));
$canCatalog = !$isGuest && ($user->can('salesEmployee') || $user->can('inventoryEmployee') || $user->can('manager') || $user->can('owner') || $user->can('admin'));
$canStock = !$isGuest && ($user->can('inventoryEmployee') || $user->can('manager') || $user->can('owner') || $user->can('admin'));
$canOrders = !$isGuest && ($user->can('salesEmployee') || $user->can('financialEmployee') || $user->can('manager') || $user->can('owner') || $user->can('admin'));
$canProduction = !$isGuest && ($user->can('inventoryEmployee') || $user->can('manager') || $user->can('owner') || $user->can('admin'));
$canDashboard = !$isGuest && ($user->can('manager') || $user->can('owner') || $user->can('admin'));
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

    <?php if ($canDashboard || $canCrm || $canCatalog || $canStock || $canOrders || $canProduction): ?>
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
            <?php if ($canStock): ?>
                <?= Html::a('📦 Inventory', ['/inventory-stock/index']) ?>
            <?php endif; ?>
            <?php if ($canDashboard): ?>
                <?= Html::a('📊 Dashboard', ['/dashboard/index']) ?>
            <?php endif; ?>
        </nav>
    <?php endif; ?>

    <div class="sidebar-section-label">Website</div>
    <nav class="sidebar-nav">
        <?= Html::a('🏠 Homepage', ['/site/index']) ?>
        <?= Html::a('ℹ️ About Us', ['/site/about']) ?>
        <?= Html::a('☎️ Contact', ['/site/contact']) ?>
        <?= Html::a('📄 Impressum', ['/site/impressum']) ?>
    </nav>

    <div class="sidebar-footer-note">Upcoming modules are shown for planning.</div>
</aside>
