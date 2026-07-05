<?php

declare(strict_types=1);

use yii\helpers\Html;
?>
<footer class="shop-style-footer">
    <div class="footer-main-area">
        <div class="container-fluid px-4">
            <div class="row gy-5">

                <!-- Brand column -->
                <div class="col-md-4">
                    <div class="footer-logo-space">
                        <?= Html::img('@web/images/blumenhof-logo.svg', [
                            'alt' => 'Blumenhof',
                            'class' => 'footer-logo-img',
                        ]) ?>
                        <p class="mt-3 mb-0">
                            Internal florist management platform for customers,
                            stock, orders, production, and daily business overview.
                        </p>
                    </div>
                </div>

                <!-- Explore column -->
                <div class="col-6 col-md-2 offset-md-1">
                    <div class="footer-column">
                        <h3 class="footer-heading">Explore</h3>
                        <?= Html::a('Home', ['/site/index']) ?>
                        <?= Html::a('About Us', ['/site/about']) ?>
                        <?= Html::a('Contact', ['/site/contact']) ?>
                    </div>
                </div>

                <!-- Company column -->
                <div class="col-6 col-md-2">
                    <div class="footer-column">
                        <h3 class="footer-heading">Company</h3>
                        <?= Html::a('Impressum', ['/site/impressum']) ?>
                        <?= Html::a('Privacy Policy', '#') ?>
                        <?= Html::a('Terms', '#') ?>
                    </div>
                </div>

                <!-- Connect column -->
                <div class="col-md-3">
                    <div class="footer-column">
                        <h3 class="footer-heading">Connect</h3>
                        <div class="footer-social">
                            <a href="#" aria-label="Facebook">f</a>
                            <a href="#" aria-label="Instagram">◎</a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> blumenHof. All rights reserved.</p>
                <p>COSD Project · Hof University</p>
            </div>
        </div>
    </div>
</footer>
