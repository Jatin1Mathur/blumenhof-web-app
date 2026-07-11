<?php

use frontend\assets\DashboardAsset;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */

DashboardAsset::register($this);

$this->title = 'Dashboard';

$ordersSummaryUrl = Url::to(['dashboard/orders-summary-data']);
$topProductsUrl = Url::to(['dashboard/top-products-data']);
$stockProductionUrl = Url::to(['dashboard/stock-production-data']);
$canViewFinance = Yii::$app->user->can('viewFinance');
$financeRevenueUrl = $canViewFinance ? Url::to(['dashboard/finance-revenue-data']) : null;
$topCustomersUrl = Url::to(['dashboard/top-customers-data']);
$lostClientsUrl = Url::to(['dashboard/lost-clients-data']);
?>
<h1><?= Html::encode($this->title) ?></h1>
<p class="text-body-secondary mb-4">A quick overview of what's happening across the shop.</p>

<div class="dash-kpi-row">
    <div class="dash-kpi" id="kpi-week">
        <div class="dash-kpi-value">&hellip;</div>
        <div class="dash-kpi-label">Orders this week</div>
    </div>
    <div class="dash-kpi" id="kpi-month">
        <div class="dash-kpi-value">&hellip;</div>
        <div class="dash-kpi-label">Orders this month</div>
    </div>
    <div class="dash-kpi" id="kpi-lowstock">
        <div class="dash-kpi-value">&hellip;</div>
        <div class="dash-kpi-label">Low-stock items</div>
    </div>
    <?php if ($canViewFinance): ?>
        <div class="dash-kpi" id="kpi-revenue">
            <div class="dash-kpi-value">&hellip;</div>
            <div class="dash-kpi-label">Revenue this month</div>
        </div>
    <?php endif; ?>
</div>

<div class="dash-grid">

    <div class="dash-card dash-appear" style="--dash-delay: 0s">
        <h3>📦 Stock Overview</h3>
        <canvas id="stockChart"></canvas>
    </div>

    <div class="dash-card dash-appear" style="--dash-delay: 0.08s">
        <h3>🌟 Best-Selling Products</h3>
        <canvas id="topProductsChart"></canvas>
    </div>

    <?php if ($canViewFinance): ?>
        <div class="dash-card dash-appear" style="--dash-delay: 0.16s">
            <h3>💰 Revenue — Last 6 Months</h3>
            <canvas id="financeRevenueChart"></canvas>
        </div>
    <?php endif; ?>

    <div class="dash-card dash-appear" style="--dash-delay: 0.24s">
        <h3>📈 Sales — Week / Month</h3>
        <canvas id="ordersSummaryChart"></canvas>
    </div>

</div>

<div class="dash-more-section">
    <h3 class="dash-more-heading">More Insights</h3>
    <div class="dash-more-buttons">
        <button type="button" class="btn btn-outline-secondary dash-toggle-btn" data-target="dash-panel-customers">👥 Top Customers</button>
        <button type="button" class="btn btn-outline-secondary dash-toggle-btn" data-target="dash-panel-production">🏭 Production Overview</button>
        <button type="button" class="btn btn-outline-secondary dash-toggle-btn" data-target="dash-panel-lost">⚠️ Lost Clients</button>
    </div>

    <div id="dash-panel-customers" class="dash-card dash-more-panel" style="display:none;">
        <h3>👥 Top Customers</h3>
        <canvas id="topCustomersChart"></canvas>
    </div>

    <div id="dash-panel-production" class="dash-card dash-more-panel" style="display:none;">
        <h3>🏭 Production Overview</h3>
        <canvas id="productionChart"></canvas>
    </div>

    <div id="dash-panel-lost" class="dash-card dash-more-panel" style="display:none;">
        <h3>Lost Clients (no order in 1-3 months)</h3>
        <table class="table" id="lostClientsTable">
            <thead>
                <tr><th>Company</th><th>Last Order</th></tr>
            </thead>
            <tbody id="lostClientsBody"></tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(32, 32, 32, 0.92)';
    Chart.defaults.plugins.tooltip.titleFont = { weight: 'bold', size: 13 };
    Chart.defaults.plugins.tooltip.bodyFont = { size: 12 };
    Chart.defaults.plugins.tooltip.padding = 10;
    Chart.defaults.plugins.tooltip.cornerRadius = 8;
    Chart.defaults.plugins.tooltip.displayColors = false;

    fetch('<?= $stockProductionUrl ?>')
        .then(function (response) { return response.json(); })
        .then(function (data) {
            var ctx = document.getElementById('stockChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.stock.labels,
                    datasets: [{
                        label: 'Products',
                        data: data.stock.data,
                        backgroundColor: ['#f28e2b', '#edc948', '#e15759'],
                        borderRadius: 8,
                    }],
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(ctx) { var total = ctx.chart.data.datasets[0].data.reduce(function(a,b){return a+b;},0); var pct = total ? Math.round(ctx.parsed.x / total * 100) : 0; return ctx.label + ': ' + ctx.parsed.x + ' products (' + pct + '% of total)'; } } } },
                    scales: { x: { beginAtZero: true, ticks: { precision: 0 } } },
                },
            });

            var lowStockCount = data.stock.data[0] || 0;
            document.querySelector('#kpi-lowstock .dash-kpi-value').textContent = lowStockCount;
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
                        borderRadius: 8,
                    }],
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(ctx) { return ctx.label + ': ' + ctx.parsed.x + ' units ordered'; } } } },
                    scales: { x: { beginAtZero: true, ticks: { precision: 0 } } },
                },
            });
        });

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
                        backgroundColor: ['#4e79a7', '#9acb31'],
                        borderRadius: 8,
                    }],
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(ctx) { return ctx.parsed.y + ' orders'; } } } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
                },
            });

            document.querySelector('#kpi-week .dash-kpi-value').textContent = data.data[0];
            document.querySelector('#kpi-month .dash-kpi-value').textContent = data.data[1];
        });

    <?php if ($canViewFinance): ?>
    fetch('<?= $financeRevenueUrl ?>')
        .then(function (response) { return response.json(); })
        .then(function (data) {
            var ctx = document.getElementById('financeRevenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Revenue (\u20ac)',
                        data: data.data,
                        backgroundColor: '#af7aa1',
                        borderRadius: 8,
                    }],
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(ctx) { return '€' + ctx.parsed.y.toFixed(2); } } } },
                    scales: { y: { beginAtZero: true } },
                },
            });

            var lastMonthRevenue = data.data[data.data.length - 1] || 0;
            document.querySelector('#kpi-revenue .dash-kpi-value').textContent = '\u20ac' + lastMonthRevenue.toFixed(0);
        });
    <?php endif; ?>

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
                        borderRadius: 8,
                    }],
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(ctx) { return ctx.label + ': ' + ctx.parsed.x + ' orders'; } } } },
                    scales: { x: { beginAtZero: true, ticks: { precision: 0 } } },
                },
            });
        });

    fetch('<?= $stockProductionUrl ?>')
        .then(function (response) { return response.json(); })
        .then(function (data) {
            var ctx = document.getElementById('productionChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.production.labels,
                    datasets: [{
                        label: 'Tasks',
                        data: data.production.data,
                        backgroundColor: ['#bab0ac', '#4e79a7', '#59a14f'],
                        borderRadius: 8,
                    }],
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(ctx) { var total = ctx.chart.data.datasets[0].data.reduce(function(a,b){return a+b;},0); var pct = total ? Math.round(ctx.parsed.x / total * 100) : 0; return ctx.label + ': ' + ctx.parsed.x + ' tasks (' + pct + '% of total)'; } } } },
                    scales: { x: { beginAtZero: true, ticks: { precision: 0 } } },
                },
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

    document.querySelectorAll('.dash-toggle-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var panel = document.getElementById(btn.dataset.target);
            var isHidden = panel.style.display === 'none';
            panel.style.display = isHidden ? 'block' : 'none';
            btn.classList.toggle('active', isHidden);
            if (isHidden) {
                panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    });
});
</script>
