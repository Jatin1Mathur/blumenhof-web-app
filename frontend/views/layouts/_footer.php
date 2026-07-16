<?php

declare(strict_types=1);

use yii\helpers\Html;
?>

<footer class="bh-footer">
    <div class="container-fluid px-4">

        <div class="bh-footer-main">

            <div class="bh-footer-brand">
                <?= Html::a(
                    Html::img('@web/images/blumenhof-logo.svg', [
                        'alt' => 'Blumenhof',
                        'class' => 'bh-footer-logo',
                    ]),
                    ['/site/index'],
                    [
                        'class' => 'bh-footer-logo-link',
                        'aria-label' => 'Blumenhof home',
                    ]
                ) ?>

                <p>
                    Internal florist management platform for orders,
                    customers, inventory and production.
                </p>

                <span class="bh-footer-badge">
                    Florist management platform
                </span>
            </div>

            <nav class="bh-footer-links" aria-label="Footer navigation">
                <div>
                    <h2>Navigation</h2>

                    <?= Html::a('Home', ['/site/index']) ?>
                    <?= Html::a('About', ['/site/about']) ?>
                    <?= Html::a('Contact', ['/site/contact']) ?>
                </div>

                <div>
                    <h2>Legal</h2>

                    <?= Html::a('Impressum', ['/site/impressum']) ?>
                    <?= Html::a('Privacy Policy', ['/site/privacy']) ?>
                </div>
            </nav>

        </div>

        <div class="bh-footer-bottom">
            <p>
                &copy; <?= date('Y') ?> blumenHof.
                All rights reserved.
            </p>

            <p>
                COSD Project
                <span aria-hidden="true">•</span>
                Hof University
            </p>
        </div>

    </div>
</footer>
