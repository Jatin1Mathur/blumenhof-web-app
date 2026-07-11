<?php

declare(strict_types=1);

use yii\helpers\Html;

$currentRoute = Yii::$app->controller->route;
?>

<header class="blumen-header">

    <!-- Green welcome strip -->
    <div class="top-green-strip">
        FRESH FLOWERS &middot; SMART MANAGEMENT &middot; COSD PROJECT
    </div>

    <!-- Main header -->
    <div class="main-shop-header">
        <div class="container-fluid px-4">

            <div class="header-grid">

                <!-- Left: hamburger + navigation -->
                <div class="header-left-group">
                    <?= Html::button(
                        '<span></span><span></span><span></span>',
                        [
                            'id' => 'sidebar-toggle',
                            'class' => 'hamburger-btn',
                            'aria-label' => 'Open menu',
                            'aria-expanded' => 'false',
                        ]
                    ) ?>

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

                    </nav>
                </div>

                <!-- Center logo space -->
                <div class="header-logo-area">
                    <?= Html::a(
                        Html::img('@web/images/blumenhof-logo.svg', [
                            'alt' => 'Blumenhof',
                            'class' => 'header-logo-img',
                        ]),
                        ['/site/index']
                    ) ?>
                    <div class="header-brand-subtitle">Florist Management Platform</div>
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
