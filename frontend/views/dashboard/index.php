<?php

declare(strict_types=1);

use common\models\Order;
use frontend\assets\DashboardAsset;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Order[] $recentOrders */
/** @var common\models\Order[] $upcomingDeliveries */
/** @var int $overdueOrderCount */

DashboardAsset::register($this);

$this->title = 'Dashboard';

$user = Yii::$app->user;

$canViewFinance = $user->can('viewFinance');
$canViewOrders = $user->can('viewOrders');
$canViewCatalog = $user->can('viewCatalog');
$canViewProduction = $user->can('viewProduction');
$canViewCrm = $user->can('viewCrm');

$displayName = $user->identity->username ?? 'Team member';

$getCustomerName = static function (Order $order): string {
    if ($order->company !== null) {
        return (string) $order->company->name;
    }

    if ($order->contact !== null) {
        $name = trim(
            (string) $order->contact->first_name
            . ' '
            . (string) $order->contact->last_name
        );

        return $name !== '' ? $name : 'Individual customer';
    }

    return 'Unknown customer';
};

$statusClasses = [
    Order::STATUS_DRAFT => 'draft',
    Order::STATUS_CONFIRMED => 'confirmed',
    Order::STATUS_IN_PREPARATION => 'preparation',
    Order::STATUS_READY => 'ready',
    Order::STATUS_DELIVERED => 'delivered',
    Order::STATUS_COMPLETED => 'completed',
];
?>

<section
    class="executive-dashboard"
    data-dashboard
    data-orders-url="<?= Html::encode(Url::to(['dashboard/orders-summary-data'])) ?>"
    data-products-url="<?= Html::encode(Url::to(['dashboard/top-products-data'])) ?>"
    data-stock-url="<?= Html::encode(Url::to(['dashboard/stock-production-data'])) ?>"
    data-customers-url="<?= Html::encode(Url::to(['dashboard/top-customers-data'])) ?>"
    data-lost-url="<?= Html::encode(Url::to(['dashboard/lost-clients-data'])) ?>"
    data-finance-url="<?= $canViewFinance
        ? Html::encode(Url::to(['dashboard/finance-revenue-data']))
        : ''
    ?>"
>
    <header class="dashboard-welcome">
        <div class="dashboard-welcome-copy">
            <span class="dashboard-overline">Business workspace</span>

            <h1>
                Good <?= date('H') < 12
                    ? 'morning'
                    : (date('H') < 18 ? 'afternoon' : 'evening')
                ?>,
                <?= Html::encode($displayName) ?>
            </h1>

            <p>
                Here is the latest overview of orders, inventory,
                production and customer activity.
            </p>

            <div class="dashboard-date">
                <span class="dashboard-live-dot"></span>

                <?= Html::encode(
                    Yii::$app->formatter->asDate(
                        time(),
                        'php:l, d F Y'
                    )
                ) ?>
            </div>
        </div>

        <div class="dashboard-header-actions">
            <?php if ($canViewOrders): ?>
                <?= Html::a(
                    '+ New order',
                    ['/order/create'],
                    ['class' => 'btn btn-success']
                ) ?>

                <?= Html::a(
                    'View orders',
                    ['/order/index'],
                    ['class' => 'btn btn-outline-success']
                ) ?>
            <?php endif; ?>
        </div>
    </header>

    <div class="dashboard-kpi-grid">
        <article class="dashboard-kpi">
            <span class="dashboard-kpi-icon">01</span>

            <div>
                <span>Orders this week</span>
                <strong id="dashboard-week-orders">
                    <i class="dashboard-skeleton"></i>
                </strong>
            </div>
        </article>

        <article class="dashboard-kpi">
            <span class="dashboard-kpi-icon">02</span>

            <div>
                <span>Orders this month</span>
                <strong id="dashboard-month-orders">
                    <i class="dashboard-skeleton"></i>
                </strong>
            </div>
        </article>

        <article class="dashboard-kpi dashboard-kpi-alert">
            <span class="dashboard-kpi-icon">03</span>

            <div>
                <span>Low-stock products</span>
                <strong id="dashboard-low-stock">
                    <i class="dashboard-skeleton"></i>
                </strong>
            </div>
        </article>

        <?php if ($canViewFinance): ?>
            <article class="dashboard-kpi dashboard-kpi-revenue">
                <span class="dashboard-kpi-icon">04</span>

                <div>
                    <span>Revenue this month</span>
                    <strong id="dashboard-revenue">
                        <i class="dashboard-skeleton"></i>
                    </strong>
                </div>
            </article>
        <?php endif; ?>
    </div>

    <section class="dashboard-attention">
        <header>
            <div>
                <span class="dashboard-overline">Attention required</span>
                <h2>Operational alerts</h2>
            </div>

            <span class="dashboard-updated" data-dashboard-updated>
                Updating…
            </span>
        </header>

        <div class="dashboard-alert-grid">
            <?php if ($canViewCatalog): ?>
                <?= Html::a(
                    '<span class="dashboard-alert-symbol">S</span>'
                    . '<div><strong id="alert-low-stock">…</strong>'
                    . '<span>Low-stock products</span></div>',
                    ['/stock-warning/index'],
                    ['class' => 'dashboard-alert-card']
                ) ?>

                <?= Html::a(
                    '<span class="dashboard-alert-symbol">E</span>'
                    . '<div><strong id="alert-expiring">…</strong>'
                    . '<span>Expiring soon</span></div>',
                    ['/stock-warning/index'],
                    ['class' => 'dashboard-alert-card']
                ) ?>
            <?php endif; ?>

            <?php if ($canViewOrders): ?>
                <?= Html::a(
                    '<span class="dashboard-alert-symbol">O</span>'
                    . '<div><strong>'
                    . Html::encode((string) $overdueOrderCount)
                    . '</strong><span>Overdue orders</span></div>',
                    ['/order/index'],
                    ['class' => 'dashboard-alert-card']
                ) ?>
            <?php endif; ?>

            <?php if ($canViewProduction): ?>
                <?= Html::a(
                    '<span class="dashboard-alert-symbol">P</span>'
                    . '<div><strong id="alert-production">…</strong>'
                    . '<span>Active production tasks</span></div>',
                    ['/production-order/index'],
                    ['class' => 'dashboard-alert-card']
                ) ?>
            <?php endif; ?>
        </div>
    </section>

    <section class="dashboard-section">
        <div class="dashboard-section-heading">
            <div>
                <span class="dashboard-overline">Performance</span>
                <h2>Business activity</h2>
                <p>Current order, revenue and product performance.</p>
            </div>
        </div>

        <div class="dashboard-primary-grid">
            <article class="dashboard-panel dashboard-panel-wide">
                <header class="dashboard-panel-header">
                    <div>
                        <span class="dashboard-panel-icon">O</span>

                        <div>
                            <h3>Order activity</h3>
                            <p>Orders created during the current period.</p>
                        </div>
                    </div>

                    <?php if ($canViewOrders): ?>
                        <?= Html::a(
                            'Open orders →',
                            ['/order/index'],
                            ['class' => 'dashboard-panel-link']
                        ) ?>
                    <?php endif; ?>
                </header>

                <div class="dashboard-chart dashboard-chart-medium">
                    <canvas id="ordersSummaryChart"></canvas>
                </div>
            </article>

            <article class="dashboard-panel">
                <header class="dashboard-panel-header">
                    <div>
                        <span class="dashboard-panel-icon">S</span>

                        <div>
                            <h3>Stock condition</h3>
                            <p>Products currently requiring attention.</p>
                        </div>
                    </div>
                </header>

                <div class="dashboard-chart dashboard-chart-medium">
                    <canvas id="stockChart"></canvas>
                </div>
            </article>

            <article class="dashboard-panel">
                <header class="dashboard-panel-header">
                    <div>
                        <span class="dashboard-panel-icon">P</span>

                        <div>
                            <h3>Top products</h3>
                            <p>Best sellers based on ordered quantity.</p>
                        </div>
                    </div>

                    <?php if ($canViewCatalog): ?>
                        <?= Html::a(
                            'Catalogue →',
                            ['/product/index'],
                            ['class' => 'dashboard-panel-link']
                        ) ?>
                    <?php endif; ?>
                </header>

                <div class="dashboard-chart dashboard-chart-large">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </article>

            <?php if ($canViewFinance): ?>
                <article class="dashboard-panel">
                    <header class="dashboard-panel-header">
                        <div>
                            <span class="dashboard-panel-icon">€</span>

                            <div>
                                <h3>Revenue trend</h3>
                                <p>Revenue generated over six months.</p>
                            </div>
                        </div>
                    </header>

                    <div class="dashboard-chart dashboard-chart-large">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </article>
            <?php endif; ?>
        </div>
    </section>

    <section class="dashboard-section">
        <div class="dashboard-section-heading">
            <div>
                <span class="dashboard-overline">Daily workflow</span>
                <h2>Orders and deliveries</h2>
                <p>Recent customer orders and upcoming deadlines.</p>
            </div>
        </div>

        <div class="dashboard-workflow-grid">
            <article class="dashboard-panel">
                <header class="dashboard-panel-header">
                    <div>
                        <span class="dashboard-panel-icon">R</span>

                        <div>
                            <h3>Recent orders</h3>
                            <p>The six most recently created orders.</p>
                        </div>
                    </div>

                    <?php if ($canViewOrders): ?>
                        <?= Html::a(
                            'View all →',
                            ['/order/index'],
                            ['class' => 'dashboard-panel-link']
                        ) ?>
                    <?php endif; ?>
                </header>

                <?php if ($recentOrders === []): ?>
                    <div class="dashboard-empty">
                        No orders have been created yet.
                    </div>
                <?php else: ?>
                    <div class="dashboard-order-list">
                        <?php foreach ($recentOrders as $order): ?>
                            <?php
                            $statusClass = $statusClasses[$order->status]
                                ?? 'draft';
                            ?>

                            <?= Html::a(
                                '<div class="dashboard-order-main">'
                                . '<span class="dashboard-order-number">#'
                                . Html::encode((string) $order->id)
                                . '</span><div><strong>'
                                . Html::encode($getCustomerName($order))
                                . '</strong><small>'
                                . count($order->items)
                                . ' item'
                                . (count($order->items) === 1 ? '' : 's')
                                . '</small></div></div>'
                                . '<div class="dashboard-order-meta">'
                                . '<span class="dashboard-order-status '
                                . 'status-' . Html::encode($statusClass) . '">'
                                . Html::encode($order->status)
                                . '</span><strong>€'
                                . Html::encode(
                                    number_format($order->getTotal(), 2)
                                )
                                . '</strong></div>',
                                ['/order/view', 'id' => $order->id],
                                ['class' => 'dashboard-order-row']
                            ) ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </article>

            <article class="dashboard-panel">
                <header class="dashboard-panel-header">
                    <div>
                        <span class="dashboard-panel-icon">D</span>

                        <div>
                            <h3>Upcoming deliveries</h3>
                            <p>Orders scheduled for their next delivery.</p>
                        </div>
                    </div>
                </header>

                <?php if ($upcomingDeliveries === []): ?>
                    <div class="dashboard-empty">
                        No upcoming deliveries are currently scheduled.
                    </div>
                <?php else: ?>
                    <div class="dashboard-delivery-list">
                        <?php foreach ($upcomingDeliveries as $order): ?>
                            <?php
                            $deliveryTimestamp = strtotime(
                                (string) $order->delivery_date
                            );

                            $isToday = $order->delivery_date === date('Y-m-d');
                            ?>

                            <?= Html::a(
                                '<div class="dashboard-delivery-date">'
                                . '<strong>'
                                . Html::encode(
                                    $isToday
                                        ? 'Today'
                                        : date('d', $deliveryTimestamp)
                                )
                                . '</strong><span>'
                                . Html::encode(
                                    $isToday
                                        ? date('M', $deliveryTimestamp)
                                        : date('M', $deliveryTimestamp)
                                )
                                . '</span></div>'
                                . '<div class="dashboard-delivery-info">'
                                . '<strong>'
                                . Html::encode($getCustomerName($order))
                                . '</strong><span>Order #'
                                . Html::encode((string) $order->id)
                                . ' · '
                                . Html::encode($order->status)
                                . '</span></div>'
                                . '<span class="dashboard-delivery-arrow">→</span>',
                                ['/order/view', 'id' => $order->id],
                                ['class' => 'dashboard-delivery-row']
                            ) ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </article>
        </div>
    </section>

    <section class="dashboard-section">
        <div class="dashboard-section-heading">
            <div>
                <span class="dashboard-overline">Operations</span>
                <h2>Production and customers</h2>
                <p>Additional operational and customer information.</p>
            </div>
        </div>

        <div class="dashboard-secondary-grid">
            <article class="dashboard-panel">
                <header class="dashboard-panel-header">
                    <div>
                        <span class="dashboard-panel-icon">W</span>

                        <div>
                            <h3>Production workload</h3>
                            <p>Pending, active and completed tasks.</p>
                        </div>
                    </div>
                </header>

                <div class="dashboard-chart dashboard-chart-medium">
                    <canvas id="productionChart"></canvas>
                </div>
            </article>

            <article class="dashboard-panel">
                <header class="dashboard-panel-header">
                    <div>
                        <span class="dashboard-panel-icon">C</span>

                        <div>
                            <h3>Top customers</h3>
                            <p>Companies ranked by order count.</p>
                        </div>
                    </div>
                </header>

                <div class="dashboard-chart dashboard-chart-medium">
                    <canvas id="customersChart"></canvas>
                </div>
            </article>

            <article class="dashboard-panel dashboard-lost-panel">
                <header class="dashboard-panel-header">
                    <div>
                        <span class="dashboard-panel-icon">!</span>

                        <div>
                            <h3>Customers requiring attention</h3>
                            <p>Companies without a recent order.</p>
                        </div>
                    </div>

                    <?php if ($canViewCrm): ?>
                        <?= Html::a(
                            'Open CRM →',
                            ['/customer-company/index'],
                            ['class' => 'dashboard-panel-link']
                        ) ?>
                    <?php endif; ?>
                </header>

                <div class="dashboard-table-wrapper">
                    <table class="table dashboard-table">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Last order</th>
                            </tr>
                        </thead>

                        <tbody id="lostClientsBody">
                            <tr>
                                <td colspan="2">Loading customer data…</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </article>
        </div>
    </section>

    <section class="dashboard-quick-section">
        <div>
            <span class="dashboard-overline">Quick access</span>
            <h2>Continue working</h2>
        </div>

        <div class="dashboard-quick-grid">
            <?php if ($canViewOrders): ?>
                <?= Html::a(
                    '<span>01</span><strong>Orders</strong>'
                    . '<small>Manage customer orders and invoices</small>',
                    ['/order/index'],
                    ['class' => 'dashboard-quick-card']
                ) ?>
            <?php endif; ?>

            <?php if ($canViewCatalog): ?>
                <?= Html::a(
                    '<span>02</span><strong>Catalogue</strong>'
                    . '<small>Manage products and categories</small>',
                    ['/product/index'],
                    ['class' => 'dashboard-quick-card']
                ) ?>

                <?= Html::a(
                    '<span>03</span><strong>Inventory</strong>'
                    . '<small>Monitor quantities and expiry dates</small>',
                    ['/inventory-stock/index'],
                    ['class' => 'dashboard-quick-card']
                ) ?>
            <?php endif; ?>

            <?php if ($canViewProduction): ?>
                <?= Html::a(
                    '<span>04</span><strong>Production</strong>'
                    . '<small>Review production tasks and progress</small>',
                    ['/production-order/index'],
                    ['class' => 'dashboard-quick-card']
                ) ?>
            <?php endif; ?>
        </div>
    </section>
</section>
