<?php

declare(strict_types=1);

use yii\helpers\Html;

$currentRoute = Yii::$app->controller->route;

$isActive = static function (string $route) use ($currentRoute): string {
    return $currentRoute === $route ? 'active' : '';
};
?>

<header class="blumen-header" role="banner">

    <div class="top-green-strip">
        <span>Fresh flowers</span>
        <span aria-hidden="true">•</span>
        <span>Smart management</span>
        <span aria-hidden="true">•</span>
        <span>COSD project</span>
    </div>

    <div class="main-shop-header">
        <div class="container-fluid px-3 px-lg-4">

            <div class="header-grid">

                <div class="header-left-group">
                    <?= Html::button(
                        '<span></span><span></span><span></span>',
                        [
                            'id' => 'sidebar-toggle',
                            'class' => 'hamburger-btn',
                            'aria-label' => 'Open navigation menu',
                            'aria-controls' => 'app-sidebar',
                            'aria-expanded' => 'false',
                            'type' => 'button',
                        ],
                    ) ?>

                    <nav class="header-left-nav" aria-label="Main navigation">
                        <?= Html::a(
                            'Home',
                            ['/site/index'],
                            ['class' => $isActive('site/index')],
                        ) ?>

                        <?= Html::a(
                            'About',
                            ['/site/about'],
                            ['class' => $isActive('site/about')],
                        ) ?>

                        <?= Html::a(
                            'Contact',
                            ['/site/contact'],
                            ['class' => $isActive('site/contact')],
                        ) ?>
                    </nav>
                </div>

                <div class="header-logo-area">
                    <?= Html::a(
                        Html::img('@web/images/blumenhof-logo.svg', [
                            'alt' => 'blumenHof home',
                            'class' => 'header-logo-img',
                        ]),
                        ['/site/index'],
                        ['class' => 'header-logo-link'],
                    ) ?>

                    <div class="header-brand-subtitle">
                        Florist Management Platform
                    </div>
                </div>

                <nav class="header-right-nav" aria-label="Account navigation">
                    <?php if (Yii::$app->user->isGuest): ?>

                        <?= Html::a(
                            'Sign up',
                            ['/site/signup'],
                            [
                                'class' => 'header-signup-link '
                                    . $isActive('site/signup'),
                            ],
                        ) ?>

                        <?= Html::a(
                            'Login',
                            ['/site/login'],
                            [
                                'class' => 'header-login-link '
                                    . $isActive('site/login'),
                            ],
                        ) ?>

                    <?php else: ?>

                        <span class="header-user-name">
                            <?= Html::encode(
                                Yii::$app->user->identity?->username
                            ) ?>
                        </span>

                        <?= Html::a(
                            'Logout',
                            ['/site/logout'],
                            [
                                'class' => 'header-login-link',
                                'data-method' => 'post',
                            ],
                        ) ?>

                    <?php endif; ?>
                </nav>

            </div>

        </div>
    </div>

</header>
