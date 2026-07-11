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
                        <?= Html::a('Privacy Policy', ['/site/privacy']) ?>
                        <?= Html::a('Terms', ['/site/terms']) ?>
                    </div>
                </div>

                <!-- Connect column -->
                <div class="col-md-3">
                    <div class="footer-column">
                        <h3 class="footer-heading">Connect</h3>
                        <div class="footer-social">
                            <a href="#" aria-label="Facebook">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                    <path d="M13.5 21v-8.2h2.7l.4-3.2h-3.1V7.4c0-.9.3-1.5 1.6-1.5H16.7V3.1C16.4 3.1 15.4 3 14.3 3c-2.3 0-3.9 1.4-3.9 4v2.6H7.7v3.2h2.7V21h3.1z"/>
                                </svg>
                            </a>
                            <a href="#" aria-label="Instagram">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                    <path d="M12 2.2c2.7 0 3 0 4.1.1 1 .1 1.6.2 2 .4.5.2.9.4 1.2.8.4.3.6.7.8 1.2.2.4.3 1 .4 2 .1 1.1.1 1.4.1 4.1s0 3-.1 4.1c-.1 1-.2 1.6-.4 2-.2.5-.4.9-.8 1.2-.3.4-.7.6-1.2.8-.4.2-1 .3-2 .4-1.1.1-1.4.1-4.1.1s-3 0-4.1-.1c-1-.1-1.6-.2-2-.4-.5-.2-.9-.4-1.2-.8-.4-.3-.6-.7-.8-1.2-.2-.4-.3-1-.4-2-.1-1.1-.1-1.4-.1-4.1s0-3 .1-4.1c.1-1 .2-1.6.4-2 .2-.5.4-.9.8-1.2.3-.4.7-.6 1.2-.8.4-.2 1-.3 2-.4 1.1-.1 1.4-.1 4.1-.1M12 0C9.3 0 8.9 0 7.8.1c-1.2.1-2 .3-2.7.6-.7.3-1.4.7-2 1.3-.6.6-1 1.2-1.3 2-.3.7-.5 1.5-.6 2.7C1.1 7.7 1 8.1 1 10.8v2.4c0 2.7 0 3.1.1 4.2.1 1.2.3 2 .6 2.7.3.7.7 1.4 1.3 2 .6.6 1.2 1 2 1.3.7.3 1.5.5 2.7.6C8.9 24 9.3 24 12 24s3.1 0 4.2-.1c1.2-.1 2-.3 2.7-.6.7-.3 1.4-.7 2-1.3.6-.6 1-1.2 1.3-2 .3-.7.5-1.5.6-2.7.1-1.1.1-1.5.1-4.2v-2.4c0-2.7 0-3.1-.1-4.2-.1-1.2-.3-2-.6-2.7-.3-.7-.7-1.4-1.3-2-.6-.6-1.2-1-2-1.3-.7-.3-1.5-.5-2.7-.6C15.1 0 14.7 0 12 0z"/>
                                    <path d="M12 5.8a6.2 6.2 0 1 0 0 12.4 6.2 6.2 0 0 0 0-12.4zm0 10.2a4 4 0 1 1 0-8 4 4 0 0 1 0 8z"/>
                                    <circle cx="18.4" cy="5.6" r="1.4"/>
                                </svg>
                            </a>
                            <a href="#" aria-label="YouTube">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                    <path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.6 12 3.6 12 3.6s-7.5 0-9.4.5A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.5 9.4.5 9.4.5s7.5 0 9.4-.5a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8zM9.6 15.6V8.4l6.3 3.6z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> blumenHof. All rights reserved.</p>
                <p>COSD Project &middot; Hof University</p>
            </div>
        </div>
    </div>
</footer>
