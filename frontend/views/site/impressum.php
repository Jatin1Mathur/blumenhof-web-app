<?php
declare(strict_types=1);

use yii\helpers\Html;

$this->title = 'Impressum';
?>

<section class="about-shop-page">
    <div class="about-title-bar">
        <div class="container-fluid px-4">
            <div class="about-title-grid">
                <div></div>
                <div class="about-title-center">IMPRESSUM</div>
                <div></div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4 py-5">
        <div class="about-intro mx-auto" style="max-width: 820px;">
            <p>
                This is the official imprint for the blumenHof florist management platform.
                It provides legal, company and contact information for the internal project website.
            </p>
        </div>

        <div class="about-wide-card mt-5">
            <h2>Company Information</h2>
            <p>
                BlumenHof GmbH<br>
                Example Street 12<br>
                12345 Flower City<br>
                Germany
            </p>

            <h2 class="mt-4">Contact</h2>
            <p>
                Email: <?= Html::mailto('info@blumenhof.example') ?><br>
                Phone: +49 123 4567 890<br>
                Website: <?= Html::a('www.blumenhof.example', 'https://www.blumenhof.example', ['target' => '_blank', 'rel' => 'noopener']) ?>
            </p>

            <h2 class="mt-4">Responsible for content</h2>
            <p>
                Max Mustermann<br>
                Geschäftsführung
            </p>

            <h2 class="mt-4">Disclaimer</h2>
            <p>
                This internal management system is intended for florist shop usage and is part of a project demonstration. The information on this page is a placeholder and should be replaced by the company's actual legal information before publication.
            </p>
        </div>

        <div class="text-center mt-5">
            <?= Html::a('Back to About', ['/site/about'], ['class' => 'about-outline-btn']) ?>
            <?= Html::a('Home', ['/site/index'], ['class' => 'about-main-btn']) ?>
        </div>
    </div>
</section>
