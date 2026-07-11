<?php

declare(strict_types=1);

use yii\helpers\Html;

$this->title = 'Terms and Conditions';
?>
<section class="about-shop-page">
    <div class="about-title-bar">
        <div class="container-fluid px-4">
            <div class="about-title-grid">
                <div></div>
                <div class="about-title-center">TERMS AND CONDITIONS</div>
                <div></div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4 py-5">
        <div class="about-intro mx-auto" style="max-width: 820px;">
            <p>
                blumenHof is an internal tool intended for use by authorized staff of the
                florist shop only. By logging in, staff members agree to use the system
                responsibly and in accordance with their assigned role.
            </p>
        </div>
        <div class="about-wide-card mt-5">
            <h2>Acceptable Use</h2>
            <p>
                Accounts must not be shared between staff members. Each user is responsible
                for actions taken under their own login.
            </p>
            <h2 class="mt-4">Data Accuracy</h2>
            <p>
                Staff are expected to keep customer, order, and stock records accurate and
                up to date to the best of their ability.
            </p>
            <h2 class="mt-4">Changes</h2>
            <p>
                These terms may be updated as the platform evolves. Continued use of the
                system after an update constitutes acceptance of the revised terms.
            </p>
        </div>
        <div class="text-center mt-5">
            <?= Html::a('Back to About', ['/site/about'], ['class' => 'about-outline-btn']) ?>
            <?= Html::a('Home', ['/site/index'], ['class' => 'about-main-btn']) ?>
        </div>
    </div>
</section>
