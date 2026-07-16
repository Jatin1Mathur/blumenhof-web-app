<?php

declare(strict_types=1);

use yii\helpers\Html;

$this->title = 'Privacy Policy';
?>

<section class="legal-page privacy-page">

    <header class="legal-hero">
        <div class="legal-hero-copy">
            <span class="legal-eyebrow">
                Data and privacy
            </span>

            <h1><?= Html::encode($this->title) ?></h1>

            <p>
                This page explains what information is handled by the
                BlumenHof management platform and how that information is
                used within the academic project.
            </p>

            <div class="legal-hero-tags">
                <span>Internal system</span>
                <span>Role-based access</span>
                <span>Academic project</span>
            </div>
        </div>

        <div class="legal-hero-mark privacy-hero-mark" aria-hidden="true">
            <span>DP</span>
            <small>Data privacy</small>
        </div>
    </header>

    <div class="privacy-summary-grid">

        <article class="privacy-summary-card">
            <span>01</span>

            <div>
                <strong>Internal use</strong>
                <small>Information supports daily platform operations.</small>
            </div>
        </article>

        <article class="privacy-summary-card">
            <span>02</span>

            <div>
                <strong>Restricted access</strong>
                <small>Permissions determine what staff members can view.</small>
            </div>
        </article>

        <article class="privacy-summary-card">
            <span>03</span>

            <div>
                <strong>No marketing purpose</strong>
                <small>Project data is not intended for advertising.</small>
            </div>
        </article>

        <article class="privacy-summary-card">
            <span>04</span>

            <div>
                <strong>Academic demonstration</strong>
                <small>The platform was created for a university project.</small>
            </div>
        </article>

    </div>

    <div class="legal-layout">

        <main class="legal-content">

            <article class="legal-card">
                <header class="legal-card-header">
                    <span class="legal-section-number">01</span>

                    <div>
                        <h2>Scope of this policy</h2>
                        <p>Where this privacy information applies.</p>
                    </div>
                </header>

                <div class="privacy-copy">
                    <p>
                        This policy applies to information entered, stored
                        and processed inside the BlumenHof internal florist
                        management platform.
                    </p>

                    <p>
                        The application was developed as a university semester
                        project and demonstrates business processes such as
                        customer management, ordering, inventory control and
                        production planning.
                    </p>
                </div>
            </article>

            <article class="legal-card">
                <header class="legal-card-header">
                    <span class="legal-section-number">02</span>

                    <div>
                        <h2>Information handled by the platform</h2>
                        <p>Main information categories used by the system.</p>
                    </div>
                </header>

                <div class="privacy-data-grid">

                    <section class="privacy-data-card">
                        <span class="privacy-data-code">CU</span>

                        <div>
                            <h3>Customer information</h3>

                            <ul class="privacy-list">
                                <li>Company names and business details</li>
                                <li>Contact names and contact information</li>
                                <li>Customer communication information</li>
                            </ul>
                        </div>
                    </section>

                    <section class="privacy-data-card">
                        <span class="privacy-data-code">OR</span>

                        <div>
                            <h3>Order information</h3>

                            <ul class="privacy-list">
                                <li>Ordered products and quantities</li>
                                <li>Prices, delivery dates and notes</li>
                                <li>Order and production status</li>
                            </ul>
                        </div>
                    </section>

                    <section class="privacy-data-card">
                        <span class="privacy-data-code">IN</span>

                        <div>
                            <h3>Inventory information</h3>

                            <ul class="privacy-list">
                                <li>Product and category information</li>
                                <li>Stock quantities and warning levels</li>
                                <li>Expiry and availability information</li>
                            </ul>
                        </div>
                    </section>

                    <section class="privacy-data-card">
                        <span class="privacy-data-code">AC</span>

                        <div>
                            <h3>Staff account information</h3>

                            <ul class="privacy-list">
                                <li>User account information</li>
                                <li>Authentication information</li>
                                <li>Assigned roles and permissions</li>
                            </ul>
                        </div>
                    </section>

                </div>
            </article>

            <article class="legal-card">
                <header class="legal-card-header">
                    <span class="legal-section-number">03</span>

                    <div>
                        <h2>How information is used</h2>
                        <p>Operational purposes supported by the platform.</p>
                    </div>
                </header>

                <div class="privacy-purpose-grid">

                    <div class="privacy-purpose-item">
                        <span>01</span>

                        <div>
                            <strong>Order management</strong>

                            <p>
                                Creating, updating and tracking customer orders
                                through their fulfilment workflow.
                            </p>
                        </div>
                    </div>

                    <div class="privacy-purpose-item">
                        <span>02</span>

                        <div>
                            <strong>Customer management</strong>

                            <p>
                                Organising customer companies, contacts and
                                associated business records.
                            </p>
                        </div>
                    </div>

                    <div class="privacy-purpose-item">
                        <span>03</span>

                        <div>
                            <strong>Inventory monitoring</strong>

                            <p>
                                Tracking stock quantities, expiry dates and
                                products requiring attention.
                            </p>
                        </div>
                    </div>

                    <div class="privacy-purpose-item">
                        <span>04</span>

                        <div>
                            <strong>Production planning</strong>

                            <p>
                                Coordinating production activities connected
                                with confirmed customer orders.
                            </p>
                        </div>
                    </div>

                </div>
            </article>

            <article class="legal-card">
                <header class="legal-card-header">
                    <span class="legal-section-number">04</span>

                    <div>
                        <h2>Access and security</h2>
                        <p>How access to platform information is controlled.</p>
                    </div>
                </header>

                <div class="privacy-access-flow">

                    <div class="privacy-access-step">
                        <span>1</span>

                        <div>
                            <strong>Authentication</strong>

                            <p>
                                Staff members must sign in before accessing
                                protected platform functions.
                            </p>
                        </div>
                    </div>

                    <div class="privacy-flow-line" aria-hidden="true"></div>

                    <div class="privacy-access-step">
                        <span>2</span>

                        <div>
                            <strong>Role verification</strong>

                            <p>
                                The system checks the permissions assigned to
                                the signed-in account.
                            </p>
                        </div>
                    </div>

                    <div class="privacy-flow-line" aria-hidden="true"></div>

                    <div class="privacy-access-step">
                        <span>3</span>

                        <div>
                            <strong>Restricted functionality</strong>

                            <p>
                                Users can only view or modify modules allowed
                                by their assigned role.
                            </p>
                        </div>
                    </div>

                </div>

                <div class="privacy-security-note">
                    <strong>Security notice</strong>

                    <p>
                        Access control reduces unnecessary access, but no
                        software system can guarantee complete protection
                        against every possible security risk.
                    </p>
                </div>
            </article>

            <article class="legal-card">
                <header class="legal-card-header">
                    <span class="legal-section-number">05</span>

                    <div>
                        <h2>Sessions and technical information</h2>
                        <p>Information required for the application to operate.</p>
                    </div>
                </header>

                <div class="privacy-copy">
                    <p>
                        The application uses technical session information to
                        keep users signed in and to provide protected access
                        to platform functions.
                    </p>

                    <p>
                        Technical information may also be processed when
                        handling application requests, errors and security
                        events required for normal system operation.
                    </p>
                </div>
            </article>

            <article class="legal-card">
                <header class="legal-card-header">
                    <span class="legal-section-number">06</span>

                    <div>
                        <h2>Storage and removal</h2>
                        <p>How project information should be managed.</p>
                    </div>
                </header>

                <div class="privacy-retention-grid">

                    <div>
                        <span class="legal-label">Storage</span>

                        <p>
                            Information is stored in the project database and
                            associated application storage.
                        </p>
                    </div>

                    <div>
                        <span class="legal-label">Review</span>

                        <p>
                            Unnecessary demonstration records should be
                            reviewed and removed when they are no longer needed.
                        </p>
                    </div>

                    <div>
                        <span class="legal-label">Correction</span>

                        <p>
                            Incorrect information can be updated by authorised
                            users through the relevant management module.
                        </p>
                    </div>

                    <div>
                        <span class="legal-label">Deletion</span>

                        <p>
                            Records may be removed by users who have the
                            required management permission.
                        </p>
                    </div>

                </div>
            </article>

        </main>

        <aside class="legal-sidebar">

            <article class="legal-notice-card privacy-notice-card">
                <span class="legal-notice-label">
                    Important
                </span>

                <h2>Academic project notice</h2>

                <p>
                    BlumenHof is a fictional management platform created for
                    academic demonstration at Hof University.
                </p>

                <p>
                    This page describes the behaviour of the project and is
                    not a reviewed privacy notice for a commercial deployment.
                </p>
            </article>

            <article class="legal-side-card">
                <span class="legal-label">
                    Privacy contact
                </span>

                <h2 class="privacy-side-heading">
                    Questions or corrections
                </h2>

                <p>
                    Questions concerning project information can be submitted
                    through the platform contact page.
                </p>

                <?= Html::a(
                    'Open contact page',
                    ['/site/contact'],
                    ['class' => 'btn btn-outline-success w-100']
                ) ?>
            </article>

            <article class="legal-side-card privacy-policy-summary">
                <span class="legal-label">
                    Policy summary
                </span>

                <ul>
                    <li>Information supports internal operations</li>
                    <li>Access depends on assigned permissions</li>
                    <li>No advertising purpose is intended</li>
                    <li>The platform is an academic demonstration</li>
                </ul>
            </article>

            <nav
                class="legal-side-card legal-related-links"
                aria-label="Related legal pages"
            >
                <span class="legal-label">Related pages</span>

                <?= Html::a(
                    '<span>Impressum</span><strong>→</strong>',
                    ['/site/impressum']
                ) ?>

                <?= Html::a(
                    '<span>Terms and Conditions</span><strong>→</strong>',
                    ['/site/terms']
                ) ?>

                <?= Html::a(
                    '<span>About the project</span><strong>→</strong>',
                    ['/site/about']
                ) ?>
            </nav>

        </aside>

    </div>

    <footer class="legal-page-footer">
        <div>
            <span class="legal-eyebrow">Privacy and transparency</span>

            <h2>Questions about project information?</h2>

            <p>
                Use the contact page to request clarification or correction.
            </p>
        </div>

        <div class="legal-footer-actions">
            <?= Html::a(
                'View Impressum',
                ['/site/impressum'],
                ['class' => 'btn btn-outline-success']
            ) ?>

            <?= Html::a(
                'Contact us',
                ['/site/contact'],
                ['class' => 'btn btn-success']
            ) ?>
        </div>
    </footer>

</section>
