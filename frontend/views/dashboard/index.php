<?php

use frontend\assets\DashboardAsset;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */

DashboardAsset::register($this);

$this->title = 'Dashboard';

$topProductsUrl = Url::to(['dashboard/top-products-data']);
$topCustomersUrl = Url::to(['dashboard/top-customers-data']);
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
    fetch('<?= $topProductsUrl ?>')
        .then(function (response) { return response.json(); })
        .then(function (data) {
            var ctx = document.getElementById('topProductsChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Units Ordered',
                        data: data.data,
                        backgroundColor: '#59a14f',
                    }],
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true, ticks: { precision: 0 } } },
                },
            });
        });

    fetch('<?= $topCustomersUrl ?>')
        .then(function (response) { return response.json(); })
        .then(function (data) {
            var ctx = document.getElementById('topCustomersChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Orders',
                        data: data.data,
                        backgroundColor: '#af7aa1',
                    }],
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true, ticks: { precision: 0 } } },
                },
            });
        });
});
</script>
