<?php
declare(strict_types=1);

use frontend\widgets\FeatureList;
use frontend\widgets\InfoCard;
use frontend\widgets\SectionTitle;
use frontend\widgets\ServiceCard;
use yii\helpers\Html;

$this->title = 'About Us';
?>

<section class="about-shop-page">

    <!-- Page title bar like reference screenshot -->
    <div class="about-title-bar">
        <div class="container-fluid px-4">
            <div class="about-title-grid">
                <div></div>
                <div class="about-title-center">ABOUT BLUMENHOF</div>
                <div></div>
            </div>
        </div>
    </div>

    <!-- Main about content -->
    <div class="container-fluid px-4 py-5">

        <div class="about-intro text-center mx-auto">
            <?= SectionTitle::widget([
                'subtitle' => 'DIGITIZATION OF A FLORIST SHOP',
                'title' => 'Fresh flowers, simple workflow, and smart florist management.',
                'align' => 'center',
            ]) ?>

            <p>
                blumenHof is an internal management platform for a florist shop.
                It helps the shop owner and staff organize customers, products,
                orders, stock, production work, and business overview in one clean system.
            </p>
        </div>

        <div class="row g-5 mt-5 align-items-start">

            <div class="col-lg-4">
                <?= InfoCard::widget([
                    'number' => '01',
                    'title' => 'Who we are',
                    'body' => 'blumenHof combines traditional floral service with a modern digital workflow. The goal is to make everyday florist shop operations more organized, transparent, and manageable.',
                ]) ?>
            </div>

            <div class="col-lg-4">
                <?= InfoCard::widget([
                    'number' => '02',
                    'title' => 'Our mission',
                    'body' => 'Our mission is to support florist businesses with better customer handling, product tracking, stock awareness, order planning, and production management.',
                ]) ?>
            </div>

            <div class="col-lg-4">
                <?= InfoCard::widget([
                    'number' => '03',
                    'title' => 'Our values',
                    'body' => 'Freshness, quality, creativity, customer trust, sustainability, and continuous improvement are the core values behind blumenHof.',
                ]) ?>
            </div>

        </div>

        <!-- Wide light section -->
        <div class="about-wide-card mt-5">
            <div class="row g-4 align-items-center">

                <div class="col-lg-7">
                    <?= SectionTitle::widget([
                        'subtitle' => 'SMART INTERNAL SYSTEM',
                        'title' => 'Designed for florist shop owners and staff',
                        'align' => 'left',
                    ]) ?>
                    <p>
                        This project is not a public webshop. It is a back-office system
                        that helps manage customers, catalog articles, sales overview,
                        inventory insights, production planning, and future business growth.
                    </p>
                </div>

                <div class="col-lg-5">
                    <?= FeatureList::widget([
                        'items' => [
                            'Customer and contact management',
                            'Catalog and stock overview',
                            'Order and production planning',
                            'Dashboard for business insights',
                            'Clean company website pages',
                        ],
                    ]) ?>
                </div>

            </div>
        </div>

        <!-- Flower service style section -->
        <div class="row g-4 mt-5">
            <div class="col-md-4">
                <?= ServiceCard::widget([
                    'icon' => '💐',
                    'title' => 'Bouquets',
                    'body' => 'Fresh flower bouquets and seasonal arrangements.',
                ]) ?>
            </div>

            <div class="col-md-4">
                <?= ServiceCard::widget([
                    'icon' => '🌿',
                    'title' => 'Plants',
                    'body' => 'Indoor plants and decoration items for the shop catalog.',
                ]) ?>
            </div>

            <div class="col-md-4">
                <?= ServiceCard::widget([
                    'icon' => '📦',
                    'title' => 'Management',
                    'body' => 'Stock, orders, customers, and daily florist workflow.',
                ]) ?>
            </div>
        </div>

        <div class="text-center mt-5">
            <?= Html::a('Back to Homepage', ['/site/index'], ['class' => 'about-main-btn']) ?>
            <?= Html::a('Contact Us', ['/site/contact'], ['class' => 'about-outline-btn']) ?>
        </div>

    </div>

</section>
