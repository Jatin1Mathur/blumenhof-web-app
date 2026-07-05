<?php

use frontend\assets\DashboardAsset;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */

DashboardAsset::register($this);

$this->title = 'Dashboard';

$ordersSummaryUrl = Url::to(['dashboard/orders-summary-data']);
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('<?= $ordersSummaryUrl ?>')
        .then(function (response) { return response.json(); })
        .then(function (data) {
            var ctx = document.getElementById('ordersSummaryChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Orders',
                        data: data.data,
                        backgroundColor: ['#4e79a7', '#f28e2b', '#e15759'],
                    }],
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
                },
            });
        });
});
</script>
