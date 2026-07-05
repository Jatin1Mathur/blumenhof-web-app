<?php

use frontend\assets\DashboardAsset;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */

DashboardAsset::register($this);

$this->title = 'Dashboard';

$ordersSummaryUrl = Url::to(['dashboard/orders-summary-data']);
$topProductsUrl = Url::to(['dashboard/top-products-data']);
$topCustomersUrl = Url::to(['dashboard/top-customers-data']);
$stockProductionUrl = Url::to(['dashboard/stock-production-data']);
$lostClientsUrl = Url::to(['dashboard/lost-clients-data']);
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
        <h3>Lost Clients (no order in 1-3 months)</h3>
        <table class="table" id="lostClientsTable">
            <thead>
                <tr><th>Company</th><th>Last Order</th></tr>
            </thead>
            <tbody id="lostClientsBody">
            </tbody>
        </table>
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

    fetch('<?= $lostClientsUrl ?>')
        .then(function (response) { return response.json(); })
        .then(function (clients) {
            var tbody = document.getElementById('lostClientsBody');

            if (clients.length === 0) {
                tbody.innerHTML = '<tr><td colspan="2">No lost clients right now.</td></tr>';
                return;
            }

            clients.forEach(function (client) {
                var row = document.createElement('tr');
                var nameCell = document.createElement('td');
                var dateCell = document.createElement('td');
                nameCell.textContent = client.name;
                dateCell.textContent = client.lastOrder;
                row.appendChild(nameCell);
                row.appendChild(dateCell);
                tbody.appendChild(row);
            });
        });
});
</script>
