<?php
declare(strict_types=1);

use yii\helpers\Html;

$currentRoute = Yii::$app->controller->route;
$hasInternalLinks = !Yii::$app->user->isGuest && (
    Yii::$app->user->can('viewDashboard') || Yii::$app->user->can('manageDashboard') ||
    Yii::$app->user->can('viewCustomers') || Yii::$app->user->can('manageCustomers') ||
    Yii::$app->user->can('viewCatalog') || Yii::$app->user->can('manageCatalog') ||
    Yii::$app->user->can('viewOrders') || Yii::$app->user->can('manageOrders') ||
    Yii::$app->user->can('viewProduction') || Yii::$app->user->can('manageProduction') ||
    Yii::$app->user->can('viewAdmin') || Yii::$app->user->can('manageAdmin')
);
?>

<header class="blumen-header">

    <!-- Green welcome strip -->
    <div class="top-green-strip">
        WELCOME TO BLUMENHOF
    </div>

    <!-- Main header -->
    <div class="main-shop-header">
        <div class="container-fluid px-4">

            <div class="header-grid">

                <!-- Left navigation -->
                <div>
                    <nav class="header-left-nav">
                        <?= Html::a('Home', ['/site/index'], [
                            'class' => $currentRoute === 'site/index' ? 'active' : ''
                        ]) ?>

                        <?= Html::a('About', ['/site/about'], [
                            'class' => $currentRoute === 'site/about' ? 'active' : ''
                        ]) ?>

                        <?= Html::a('Contact', ['/site/contact'], [
                            'class' => $currentRoute === 'site/contact' ? 'active' : ''
                        ]) ?>

                        <?= Html::a('Impressum', ['/site/impressum'], [
                            'class' => $currentRoute === 'site/impressum' ? 'active' : ''
                        ]) ?>
                    </nav>

                    <?php if ($hasInternalLinks): ?>
                        <nav class="header-internal-nav">
                            <span class="internal-nav-label">Internal</span>
                            <?php if (Yii::$app->user->can('viewDashboard') || Yii::$app->user->can('manageDashboard')): ?>
                                <?= Html::a('Dashboard', ['/site/dashboard'], [
                                    'class' => $currentRoute === 'site/dashboard' ? 'active' : '',
                                ]) ?>
                            <?php endif; ?>

                            <?php if (Yii::$app->user->can('viewCustomers') || Yii::$app->user->can('manageCustomers')): ?>
                                <?= Html::a('Customers', ['/site/customers'], [
                                    'class' => $currentRoute === 'site/customers' ? 'active' : '',
                                ]) ?>
                            <?php endif; ?>

                            <?php if (Yii::$app->user->can('viewCatalog') || Yii::$app->user->can('manageCatalog')): ?>
                                <?= Html::a('Catalog', ['/site/catalog'], [
                                    'class' => $currentRoute === 'site/catalog' ? 'active' : '',
                                ]) ?>
                            <?php endif; ?>

                            <?php if (Yii::$app->user->can('viewOrders') || Yii::$app->user->can('manageOrders')): ?>
                                <?= Html::a('Orders', ['/site/orders'], [
                                    'class' => $currentRoute === 'site/orders' ? 'active' : '',
                                ]) ?>
                            <?php endif; ?>

                            <?php if (Yii::$app->user->can('viewProduction') || Yii::$app->user->can('manageProduction')): ?>
                                <?= Html::a('Production', ['/site/production'], [
                                    'class' => $currentRoute === 'site/production' ? 'active' : '',
                                ]) ?>
                            <?php endif; ?>

                            <?php if (Yii::$app->user->can('viewAdmin') || Yii::$app->user->can('manageAdmin')): ?>
                                <?= Html::a('Admin', ['/site/admin'], [
                                    'class' => $currentRoute === 'site/admin' ? 'active' : '',
                                ]) ?>
                            <?php endif; ?>
                        </nav>
                    <?php endif; ?>
                </div>

                <!-- Center logo space -->
                <div class="header-logo-area">
                    <div class="header-logo-box">
                        <span class="logo-flower">🌷</span>
                    </div>
                    <div class="header-brand-name">BLUMENHOF</div>
                    <div class="header-brand-subtitle">FLORIST MANAGEMENT</div>
                </div>

                <!-- Right links -->
                <nav class="header-right-nav">
                    <?php if (Yii::$app->user->isGuest): ?>
                        <?= Html::a('Signup', ['/site/signup']) ?>
                        <?= Html::a('Login', ['/site/login']) ?>
                    <?php else: ?>
                        <?= Html::a(
                            'Logout (' . Html::encode(Yii::$app->user->identity?->username) . ')',
                            ['/site/logout'],
                            ['data-method' => 'post']
                        ) ?>
                    <?php endif; ?>

                    <?= Html::button('🌙', [
                        'id' => 'theme-toggle',
                        'class' => 'theme-simple-btn',
                        'aria-label' => 'Switch theme',
                    ]) ?>
                </nav>

            </div>

        </div>
    </div>

</header>