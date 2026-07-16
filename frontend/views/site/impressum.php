<?php

declare(strict_types=1);

use yii\helpers\Html;

$this->title = 'Impressum';
?>

<section class="legal-page">

    <header class="legal-hero">
        <div class="legal-hero-copy">
            <span class="legal-eyebrow">
                Project information
            </span>

            <h1><?= Html::encode($this->title) ?></h1>

            <p>
                Information about the BlumenHof management platform,
                its academic purpose and the project team responsible
                for its development.
            </p>

            <div class="legal-hero-tags">
                <span>Academic project</span>
                <span>Internal platform</span>
                <span>Hof University</span>
            </div>
        </div>

        <div class="legal-hero-mark" aria-hidden="true">
            <span>BH</span>
            <small>Since 1997</small>
        </div>
    </header>

    <div class="legal-layout">

        <main class="legal-content">

            <article class="legal-card">
                <header class="legal-card-header">
                    <span class="legal-section-number">01</span>

                    <div>
                        <h2>Project information</h2>
                        <p>General information about this platform.</p>
                    </div>
                </header>

                <div class="legal-information-grid">
                    <div>
                        <span class="legal-label">Project name</span>
                        <strong>BlumenHof Management Platform</strong>
                    </div>

                    <div>
                        <span class="legal-label">Project type</span>
                        <strong>University semester project</strong>
                    </div>

                    <div>
                        <span class="legal-label">Institution</span>
                        <strong>Hof University</strong>
                    </div>

                    <div>
                        <span class="legal-label">Programme</span>
                        <strong>COSD Project</strong>
                    </div>
                </div>
            </article>

            <article class="legal-card">
                <header class="legal-card-header">
                    <span class="legal-section-number">02</span>

                    <div>
                        <h2>Project contact</h2>
                        <p>Contact information used within the demonstration.</p>
                    </div>
                </header>

                <div class="legal-contact-list">
                    <div class="legal-contact-row">
                        <span>Email</span>

                        <?= Html::mailto(
                            'info@blumenhof.de',
                            'info@blumenhof.de'
                        ) ?>
                    </div>

                    <div class="legal-contact-row">
                        <span>Contact page</span>

                        <?= Html::a(
                            'Send a message',
                            ['/site/contact']
                        ) ?>
                    </div>

                    <div class="legal-contact-row">
                        <span>Location</span>
                        <strong>Hof, Germany</strong>
                    </div>
                </div>
            </article>

            <article class="legal-card">
                <header class="legal-card-header">
                    <span class="legal-section-number">03</span>

                    <div>
                        <h2>Development team</h2>
                        <p>Team members responsible for the project.</p>
                    </div>
                </header>

                <div class="legal-team-grid">
                    <div class="legal-team-member">
                        <span>JM</span>

                        <div>
                            <strong>Jatin Mathur</strong>
                            <small>Project team</small>
                        </div>
                    </div>

                    <div class="legal-team-member">
                        <span>NS</span>

                        <div>
                            <strong>Nishita Singh</strong>
                            <small>Project team</small>
                        </div>
                    </div>

                    <div class="legal-team-member">
                        <span>SC</span>

                        <div>
                            <strong>Saurav Chugh</strong>
                            <small>Project team</small>
                        </div>
                    </div>

                    <div class="legal-team-member">
                        <span>NS</span>

                        <div>
                            <strong>Niraj Dineshkumar Sharma</strong>
                            <small>Project team</small>
                        </div>
                    </div>
                </div>
            </article>

        </main>

        <aside class="legal-sidebar">

            <article class="legal-notice-card">
                <span class="legal-notice-label">
                    Important notice
                </span>

                <h2>Academic demonstration only</h2>

                <p>
                    BlumenHof is a fictional florist scenario developed
                    as part of a university semester project.
                </p>

                <p>
                    It is not a commercially operating business and does
                    not represent a registered company offering products
                    or services.
                </p>
            </article>

            <article class="legal-side-card">
                <span class="legal-label">
                    Demonstration address
                </span>

                <address>
                    BlumenHof<br>
                    Fabrikzeile 46<br>
                    95028 Hof<br>
                    Germany
                </address>

                <p>
                    This address is displayed only as part of the fictional
                    project scenario.
                </p>
            </article>

            <nav class="legal-side-card legal-related-links"
                 aria-label="Related legal pages">

                <span class="legal-label">Related pages</span>

                <?= Html::a(
                    '<span>Privacy Policy</span><strong>→</strong>',
                    ['/site/privacy']
                ) ?>

                <?= Html::a(
                    '<span>Terms and Conditions</span><strong>→</strong>',
                    ['/site/terms']
                ) ?>

                <?= Html::a(
                    '<span>Contact</span><strong>→</strong>',
                    ['/site/contact']
                ) ?>
            </nav>

        </aside>

    </div>

    <footer class="legal-page-footer">
        <div>
            <span class="legal-eyebrow">BlumenHof</span>

            <h2>Internal florist management platform</h2>

            <p>
                Created for academic demonstration at Hof University.
            </p>
        </div>

        <div class="legal-footer-actions">
            <?= Html::a(
                'About the project',
                ['/site/about'],
                ['class' => 'btn btn-outline-success']
            ) ?>

            <?= Html::a(
                'Return home',
                ['/site/index'],
                ['class' => 'btn btn-success']
            ) ?>
        </div>
    </footer>

</section>
