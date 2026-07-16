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

<script id="blumenhof-sidebar-script">
(() => {
    'use strict';

    const toggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('app-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const closeButton = document.getElementById('sidebar-close');

    if (!toggle || !sidebar || !overlay) {
        return;
    }

    const openSidebar = () => {
        sidebar.classList.add('is-open');
        overlay.classList.add('is-visible');
        toggle.classList.add('is-open');

        /*
         * Inline styles provide a fallback if older CSS is cached.
         */
        sidebar.style.transform = 'translateX(0)';
        overlay.style.opacity = '1';
        overlay.style.visibility = 'visible';
        overlay.style.pointerEvents = 'auto';

        document.body.classList.add('sidebar-is-open');

        toggle.setAttribute('aria-expanded', 'true');
        sidebar.setAttribute('aria-hidden', 'false');
    };

    const closeSidebar = () => {
        sidebar.classList.remove('is-open');
        overlay.classList.remove('is-visible');
        toggle.classList.remove('is-open');

        sidebar.style.transform = 'translateX(-100%)';
        overlay.style.opacity = '0';
        overlay.style.visibility = 'hidden';
        overlay.style.pointerEvents = 'none';

        document.body.classList.remove('sidebar-is-open');

        toggle.setAttribute('aria-expanded', 'false');
        sidebar.setAttribute('aria-hidden', 'true');
    };

    /*
     * Capture mode and stopImmediatePropagation prevent old cached
     * click handlers from opening and immediately closing the menu.
     */
    toggle.addEventListener(
        'click',
        (event) => {
            event.preventDefault();
            event.stopImmediatePropagation();

            if (sidebar.classList.contains('is-open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        },
        true
    );

    overlay.addEventListener(
        'click',
        (event) => {
            event.preventDefault();
            event.stopImmediatePropagation();
            closeSidebar();
        },
        true
    );

    if (closeButton) {
        closeButton.setAttribute('type', 'button');

        closeButton.addEventListener(
            'click',
            (event) => {
                event.preventDefault();
                event.stopImmediatePropagation();
                closeSidebar();
                toggle.focus();
            },
            true
        );
    }

    sidebar.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', closeSidebar);
    });

    document.addEventListener('keydown', (event) => {
        if (
            event.key === 'Escape'
            && sidebar.classList.contains('is-open')
        ) {
            closeSidebar();
            toggle.focus();
        }
    });

    closeSidebar();
})();
</script>
