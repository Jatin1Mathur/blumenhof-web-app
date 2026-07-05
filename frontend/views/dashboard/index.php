<?php

use frontend\assets\DashboardAsset;
use yii\helpers\Html;

/** @var yii\web\View $this */

DashboardAsset::register($this);

$this->title = 'Dashboard';
?>
<h1><?= Html::encode($this->title) ?></h1>

<div class="row">
    <div class="col-md-6">
        <h3>Orders Summary</h3>
        <canvas id="ordersSummaryChart"></canvas>
    </div>
    <div class="col-md-6">
        <h3>Top Products</h3>
        <canvas id="topProductsChart"></canvas>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <h3>Top Customers</h3>
        <canvas id="topCustomersChart"></canvas>
    </div>
    <div class="col-md-6">
        <h3>Stock &amp; Production Overview</h3>
        <canvas id="stockProductionChart"></canvas>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <h3>Lost Clients</h3>
        <div id="lostClientsList"></div>
    </div>
</div>
