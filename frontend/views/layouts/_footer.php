<?php
declare(strict_types=1);

use yii\helpers\Html;
?>

<footer class="shop-style-footer">

    <div class="footer-main-area">
        <div class="container-fluid px-4">

            <div class="row gy-5">

                <!-- Left column -->
                <div class="col-md-3">
                    <div class="footer-column">
                        <?= Html::a('About Us', ['/site/about']) ?>
                        <?= Html::a('Privacy Policy', '#') ?>
                        <?= Html::a('Terms and Conditions', '#') ?>

                        <div class="footer-social mt-5">
                            <a href="#">f</a>
                            <a href="#">◎</a>
                        </div>
                    </div>
                </div>

                <!-- Center column -->
                <div class="col-md-3 offset-md-1">
                    <div class="footer-column">
                        <?= Html::a('Search', '#') ?>
                        <?= Html::a('Contact Us', ['/site/contact']) ?>
                        <?= Html::a('Cancel Contract', '#') ?>
                    </div>
                </div>

                <!-- Logo / company space -->
                <div class="col-md-3">
                    <div class="footer-logo-space">
                        <div class="footer-logo-placeholder">
                            <span>LOGO</span>
                        </div>

                        <h2>BLUMENHOF</h2>
                        <p>Internal florist management platform</p>
                    </div>
                </div>

                <!-- Right column -->
                <div class="col-md-2">
                    <div class="footer-column">
                        <?= Html::a('Shipping and Payment', '#') ?>
                        <?= Html::a('Returns', '#') ?>
                        <?= Html::a('Impressum', ['/site/impressum']) ?>
                    </div>
                </div>

            </div>

            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> - BLUMENHOF</p>
                <p>POWERED BY YII2 · COSD PROJECT</p>
            </div>

        </div>
    </div>

</footer>
