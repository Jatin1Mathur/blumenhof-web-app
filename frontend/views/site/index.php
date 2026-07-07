<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'blumenHof — Florist Management';
$this->params['meta_description'] = 'Internal management platform for a florist shop — customers, catalog, orders, and production in one place.';
$this->params['meta_keywords'] = 'florist, blumenhof, management, crm, orders, production';
?>
<section class="about-shop-page">

    <div class="about-title-bar">
        <div class="container-fluid px-4">
            <div class="about-title-grid">
                <div></div>
                <div class="about-title-center">BLUMENHOF FLORIST MANAGEMENT</div>
                <div></div>
            </div>
        </div>
    </div>

    <!-- Hero -->
    <div class="container-fluid px-4 py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <p class="text-uppercase fw-bold mb-2 home-eyebrow">
                    Fresh Flowers &middot; Smart Management
                </p>
                <h1 class="display-4 fw-bold mb-3">
                    Everything your shop needs,<br>in one clean system.
                </h1>
                <p class="lead text-body-secondary mb-4">
                    blumenHof helps the owner and staff manage customers, products, stock,
                    orders, and production planning — all from a single internal platform.
                </p>
                <div class="d-flex gap-2 flex-wrap">
                    <?php if (Yii::$app->user->isGuest): ?>
                        <?= Html::a('Get Started', ['/site/signup'], ['class' => 'btn btn-success btn-lg px-4']) ?>
                        <?= Html::a('Login', ['/site/login'], ['class' => 'btn btn-outline-secondary btn-lg px-4']) ?>
                    <?php else: ?>
                        <?= Html::a('Go to Dashboard', ['/dashboard/index'], ['class' => 'btn btn-success btn-lg px-4']) ?>
                    <?php endif; ?>
                    <?= Html::a('Learn More', ['/site/about'], ['class' => 'btn btn-outline-secondary btn-lg px-4']) ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="home-hero-visual">
                    <?= Html::img('@web/images/blumenhof-logo.svg', [
                        'alt' => 'Blumenhof',
                        'class' => 'home-hero-logo',
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats strip -->
    <div class="home-stats-strip">
        <div class="container-fluid px-4">
            <div class="row text-center g-4">
                <div class="col-6 col-md-3">
                    <div class="home-stat-number">5</div>
                    <div class="home-stat-label">Core Modules</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="home-stat-number">6</div>
                    <div class="home-stat-label">Order Stages</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="home-stat-number">1</div>
                    <div class="home-stat-label">Unified Dashboard</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="home-stat-number">24/7</div>
                    <div class="home-stat-label">Always Available</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feature cards -->
    <div class="container-fluid px-4 py-5">
        <div class="text-center mb-5">
            <p class="text-uppercase fw-bold mb-2 home-eyebrow">What's Inside</p>
            <h2 class="fw-bold">Built for the way a florist shop actually runs</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="home-feature-card">
                    <div class="home-feature-icon">👥</div>
                    <h3 class="h5 fw-bold mb-2">Customers</h3>
                    <p class="text-body-secondary mb-0">
                        Keep track of companies and contacts, organized by customer category.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="home-feature-card">
                    <div class="home-feature-icon">💐</div>
                    <h3 class="h5 fw-bold mb-2">Catalog &amp; Stock</h3>
                    <p class="text-body-secondary mb-0">
                        Manage products and categories, and get warned before anything runs low.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="home-feature-card">
                    <div class="home-feature-icon">🧾</div>
                    <h3 class="h5 fw-bold mb-2">Orders &amp; Production</h3>
                    <p class="text-body-secondary mb-0">
                        Track every order from draft to delivery, and plan production with ease.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA banner -->
    <div class="home-cta-banner">
        <div class="container-fluid px-4 text-center py-5">
            <h2 class="fw-bold mb-3">Ready to see it in action?</h2>
            <p class="text-body-secondary mb-4">
                Log in to explore the dashboard, or reach out if you have any questions.
            </p>
            <div class="d-flex gap-2 justify-content-center flex-wrap">
                <?php if (Yii::$app->user->isGuest): ?>
                    <?= Html::a('Login', ['/site/login'], ['class' => 'btn btn-success btn-lg px-4']) ?>
                <?php else: ?>
                    <?= Html::a('Go to Dashboard', ['/dashboard/index'], ['class' => 'btn btn-success btn-lg px-4']) ?>
                <?php endif; ?>
                <?= Html::a('Contact Us', ['/site/contact'], ['class' => 'btn btn-outline-secondary btn-lg px-4']) ?>
            </div>
        </div>
    </div>

</section>
