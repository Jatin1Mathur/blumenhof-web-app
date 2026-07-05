<?php

use frontend\assets\DashboardAsset;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */

DashboardAsset::register($this);

$this->title = 'Dashboard';

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
        <h3>Stock &amp; Production Overview</h3>
        <canvas id="stockProductionChart"></canvas>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
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
