<?php

declare(strict_types=1);

namespace frontend\assets;

use yii\bootstrap5\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Main frontend application assets.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/site.css',
        'css/page-polish.css',
        'css/home-contact.css',
        'css/about.css',
        'css/crm.css',
        'css/user-management.css',
        'css/catalog-stock.css',
        'css/orders.css',
        'css/cookie-banner.css',
        'css/footer.css',
        'css/legal.css',
        'css/auth.css',
    ];

    public $js = [
        'js/site.js',
        'js/cookie-consent.js',
        'js/crm.js',
        'js/user-management.js',
        'js/catalog-stock.js',
        'js/orders.js',
    ];

    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
    ];
}
