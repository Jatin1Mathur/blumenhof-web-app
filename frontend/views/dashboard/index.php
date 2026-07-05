<?php

use frontend\assets\DashboardAsset;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */

DashboardAsset::register($this);

$this->title = 'Dashboard';

$stockProductionUrl = Url::to(['dashboard/stock-production-data']);
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
        <h3>Stock Overview</h3>
        <canvas id="stockChart"></canvas>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <h3>Production Overview</h3>
        <canvas id="productionChart"></canvas>
    </div>
    <div class="col-md-6">
        <h3>Lost Clients</h3>
        <div id="lostClientsList"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('<?= $stockProductionUrl ?>')
        .then(function (response) { return response.json(); })
        .then(function (data) {
            var stockCtx = document.getElementById('stockChart').getContext('2d');
            new Chart(stockCtx, {
                type: 'doughnut',
                data: {
                    labels: data.stock.labels,
                    datasets: [{
                        data: data.stock.data,
                        backgroundColor: ['#f28e2b', '#edc948', '#e15759'],
                    }],
                },
                options: { responsive: true },
            });

            var productionCtx = document.getElementById('productionChart').getContext('2d');
            new Chart(productionCtx, {
                type: 'doughnut',
                data: {
                    labels: data.production.labels,
                    datasets: [{
                        data: data.production.data,
                        backgroundColor: ['#bab0ac', '#4e79a7', '#59a14f'],
                    }],
                },
                options: { responsive: true },
            });
        });
});
</script>
