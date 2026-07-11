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
                This is the official imprint for the blumenHof florist management platform,
                providing legal, company, and contact information as required under German
                law (&sect; 5 TMG) for internal and demonstration purposes.
            </p>
        </div>

        <div class="row g-4 mt-2" style="max-width: 900px; margin-left: auto; margin-right: auto;">

            <div class="col-md-6">
                <div class="about-wide-card h-100">
                    <h2>🏢 Company Information</h2>
                    <p class="mb-0">
                        <strong>BlumenHof GmbH</strong><br>
                        Fabrikzeile 46<br>
                        95028 Hof<br>
                        Germany
                    </p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="about-wide-card h-100">
                    <h2>📞 Contact</h2>
                    <p class="mb-0">
                        Email: <?= Html::mailto('info@blumenhof.de') ?><br>
                        Phone: +49 123 4567 890
                    </p>
                </div>
            </div>

            <div class="col-12">
                <div class="about-wide-card">
                    <h2>👥 Responsible for Content &amp; Development Team</h2>
                    <div class="row g-3 mt-1">
                        <div class="col-sm-6 col-md-3">
                            <strong>Jatin Mathur</strong>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <strong>Nishita Singh</strong>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <strong>Saurav Chugh</strong>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <strong>Niraj Dineshkumar Sharma</strong>
                        </div>
                    </div>
                    <p class="text-body-secondary small mb-0 mt-3">
                        COSD Semester Project &middot; Hof University
                    </p>
                </div>
            </div>

            <div class="col-12">
                <div class="about-wide-card">
                    <h2>⚠️ Disclaimer</h2>
                    <p class="mb-0">
                        This is an internal management system built as part of a university
                        semester project (COSD) at Hof University, for a fictional florist
                        shop scenario used for academic demonstration purposes only. It is
                        not a commercially operating business, and the information on this
                        page does not represent a real, registered company offering.
                    </p>
                </div>
            </div>

        </div>

        <div class="text-center mt-5">
            <?= Html::a('Back to About', ['/site/about'], ['class' => 'about-outline-btn']) ?>
            <?= Html::a('Home', ['/site/index'], ['class' => 'about-main-btn']) ?>
        </div>
    </div>
</section>
