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

    <div class="container-fluid px-4 py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <p class="text-uppercase fw-bold" style="letter-spacing:2px; color: var(--flower-green-dark);">
                    Fresh Flowers &middot; Smart Management
                </p>
                <h1 class="display-5 fw-bold mb-3">
                    Everything your shop needs, in one clean system.
                </h1>
                <p class="lead text-body-secondary">
                    blumenHof helps the owner and staff manage customers, products, stock,
                    orders, and production planning — all from a single internal platform.
                </p>
                <div class="d-flex gap-2 flex-wrap mt-4">
                    <?php if (Yii::$app->user->isGuest): ?>
                        <?= Html::a('Get Started', ['/site/signup'], ['class' => 'btn btn-success btn-lg px-4']) ?>
                        <?= Html::a('Login', ['/site/login'], ['class' => 'btn btn-outline-secondary btn-lg px-4']) ?>
                    <?php else: ?>
                        <?= Html::a('Go to Dashboard', ['/dashboard/index'], ['class' => 'btn btn-success btn-lg px-4']) ?>
                    <?php endif; ?>
                    <?= Html::a('Learn More', ['/site/about'], ['class' => 'btn btn-outline-secondary btn-lg px-4']) ?>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="p-4 border rounded-4 text-center shadow-sm">
                    <?= Html::img('@web/images/blumenhof-logo.svg', [
                        'alt' => 'Blumenhof',
                        'style' => 'max-width: 260px; width: 100%;',
                    ]) ?>
                    <p class="text-body-secondary mt-3 mb-0">Internal florist management platform</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4 pb-5">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-3 p-2">
                    <div class="card-body">
                        <h3 class="h6 fw-bold mb-2">👥 Customers</h3>
                        <p class="text-body-secondary small mb-0">
                            Keep track of companies and contacts, organized by customer category.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-3 p-2">
                    <div class="card-body">
                        <h3 class="h6 fw-bold mb-2">💐 Catalog &amp; Stock</h3>
                        <p class="text-body-secondary small mb-0">
                            Manage products, categories, and get warned before anything runs low.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-3 p-2">
                    <div class="card-body">
                        <h3 class="h6 fw-bold mb-2">🧾 Orders &amp; Production</h3>
                        <p class="text-body-secondary small mb-0">
                            Track every order from draft to delivery, and plan production with ease.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
