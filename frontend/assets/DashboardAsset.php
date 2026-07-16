<?php

declare(strict_types=1);

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

class DashboardAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/dashboard.css',
    ];

    public $js = [
        'js/vendor/chart.umd.min.js',
        'js/dashboard.js',
    ];

    public $jsOptions = [
        'position' => View::POS_HEAD,
    ];
}
