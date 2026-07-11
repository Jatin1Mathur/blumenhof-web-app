<?php

declare(strict_types=1);

use yii\helpers\Html;

$this->title = 'Privacy Policy';
?>
<section class="about-shop-page">
    <div class="about-title-bar">
        <div class="container-fluid px-4">
            <div class="about-title-grid">
                <div></div>
                <div class="about-title-center">PRIVACY POLICY</div>
                <div></div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4 py-5">
        <div class="about-intro mx-auto" style="max-width: 820px;">
            <p>
                This is an internal florist management platform built for a course project.
                It stores customer, order, and staff account data solely for the purpose of
                running the shop's day-to-day operations.
            </p>
        </div>
        <div class="about-wide-card mt-5">
            <h2>What we store</h2>
            <p>
                Customer company and contact details, order records, product and stock
                information, and staff account credentials used to log in to this system.
            </p>
            <h2 class="mt-4">Who can access it</h2>
            <p>
                Access is restricted by role: only staff members with the appropriate
                permission level can view or edit any given type of data.
            </p>
            <h2 class="mt-4">Contact</h2>
            <p>
                Questions about this policy can be directed via the
                <?= Html::a('Contact page', ['/site/contact']) ?>.
            </p>
        </div>
        <div class="text-center mt-5">
            <?= Html::a('Back to About', ['/site/about'], ['class' => 'about-outline-btn']) ?>
            <?= Html::a('Home', ['/site/index'], ['class' => 'about-main-btn']) ?>
        </div>
    </div>
</section>
