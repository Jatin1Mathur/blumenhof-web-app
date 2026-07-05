<?php

declare(strict_types=1);

namespace frontend\assets;

use yii\web\AssetBundle;

class DashboardAsset extends AssetBundle
{
    public $css = [];
    public $js = [];

    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

    public function init(): void
    {
        parent::init();
        $this->js = [
            'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js',
        ];
    }
}
