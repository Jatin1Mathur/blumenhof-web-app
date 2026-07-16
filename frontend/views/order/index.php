<?php

declare(strict_types=1);

use common\models\Order;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Order[] $orders */

$this->title = 'Orders';

$canManageOrders = Yii::$app->user->can('manageOrders');

$totalValue = 0.0;
$activeCount = 0;
$completedCount = 0;
$overdueCount = 0;

$statusCounts = array_fill_keys(Order::statusList(), 0);

$finalStatuses = [
    Order::STATUS_DELIVERED,
    Order::STATUS_COMPLETED,
];

foreach ($orders as $order) {
    $totalValue += $order->getTotal();

    if (isset($statusCounts[$order->status])) {
        $statusCounts[$order->status]++;
    }

    if ($order->status === Order::STATUS_COMPLETED) {
        $completedCount++;
    } else {
        $activeCount++;
    }

    if (
        !empty($order->delivery_date)
        && strtotime($order->delivery_date) < strtotime('today')
        && !in_array($order->status, $finalStatuses, true)
    ) {
        $overdueCount++;
    }
}
?>

<section class="orders-page" data-orders-page>

    <header class="orders-hero">
        <div>
            <span class="orders-eyebrow">
                Sales and fulfilment
            </span>

            <h1><?= Html::encode($this->title) ?></h1>

            <p>
                Track customer orders from initial draft through
                preparation, delivery and completion.
            </p>
        </div>

        <?php if ($canManageOrders): ?>
            <div class="orders-header-actions">
                <?= Html::a(
                    '+ New order',
                    ['create'],
                    ['class' => 'btn btn-success'],
                ) ?>
            </div>
        <?php endif; ?>
    </header>

    <nav class="orders-navigation" aria-label="Order management sections">
        <?= Html::a(
            'All orders',
            ['/order/index'],
            ['class' => 'orders-nav-link active'],
        ) ?>

        <?= Html::a(
            'Production',
            ['/production-order/index'],
            ['class' => 'orders-nav-link'],
        ) ?>

        <?= Html::a(
            'Customers',
            ['/customer-company/index'],
            ['class' => 'orders-nav-link'],
        ) ?>

        <?= Html::a(
            'Products',
            ['/product/index'],
            ['class' => 'orders-nav-link'],
        ) ?>
    </nav>

    <div class="orders-summary-grid">

        <article class="orders-summary-card">
            <span class="orders-summary-icon">01</span>

            <div>
                <strong><?= count($orders) ?></strong>
                <span>Total orders</span>
            </div>
        </article>

        <article class="orders-summary-card">
            <span class="orders-summary-icon">02</span>

            <div>
                <strong><?= $activeCount ?></strong>
                <span>Active orders</span>
            </div>
        </article>

        <article class="orders-summary-card">
            <span class="orders-summary-icon">03</span>

            <div>
                <strong><?= $completedCount ?></strong>
                <span>Completed orders</span>
            </div>
        </article>

        <article class="orders-summary-card">
            <span class="orders-summary-icon">04</span>

            <div>
                <strong>
                    €<?= Html::encode(number_format($totalValue, 2)) ?>
                </strong>

                <span>Total order value</span>
            </div>
        </article>

    </div>

    <?php if ($overdueCount > 0): ?>
        <div class="orders-warning">
            <span class="orders-warning-icon">!</span>

            <div>
                <strong>
                    <?= $overdueCount ?> overdue order<?= $overdueCount === 1
                        ? ''
                        : 's'
                    ?>
                </strong>

                <p>
                    These orders have passed their delivery dates and are
                    not yet delivered or completed.
                </p>
            </div>

            <button
                class="btn btn-sm btn-outline-danger"
                type="button"
                data-show-overdue
            >
                Show overdue
            </button>
        </div>
    <?php endif; ?>

    <div class="orders-status-overview">
        <?php foreach ($statusCounts as $status => $count): ?>
            <?php
            $statusKey = strtolower(
                str_replace(' ', '-', $status),
            );
            ?>

            <button
                class="orders-status-card"
                type="button"
                data-status-shortcut="<?= Html::encode(strtolower($status)) ?>"
            >
                <span class="orders-status-dot orders-status-<?= Html::encode(
                    $statusKey,
                ) ?>"></span>

                <span>
                    <strong><?= $count ?></strong>
                    <?= Html::encode($status) ?>
                </span>
            </button>
        <?php endforeach; ?>
    </div>

    <div class="orders-toolbar">

        <div class="orders-search">
            <span aria-hidden="true">⌕</span>

            <input
                type="search"
                class="form-control"
                data-order-search
                placeholder="Search by order number, customer or status..."
                aria-label="Search orders"
                autocomplete="off"
            >
        </div>

        <div class="orders-filters">
            <select
                class="form-select"
                data-order-status
                aria-label="Filter orders by status"
            >
                <option value="">All statuses</option>

                <?php foreach (Order::statusList() as $status): ?>
                    <option value="<?= Html::encode(strtolower($status)) ?>">
                        <?= Html::encode($status) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select
                class="form-select"
                data-order-delivery
                aria-label="Filter orders by delivery date"
            >
                <option value="">All delivery dates</option>
                <option value="overdue">Overdue</option>
                <option value="today">Due today</option>
                <option value="upcoming">Upcoming</option>
                <option value="none">No delivery date</option>
            </select>

            <button
                class="btn btn-outline-secondary"
                type="button"
                data-order-reset
            >
                Reset
            </button>
        </div>

    </div>

    <?php if ($orders === []): ?>

        <div class="orders-empty-state">
            <span class="orders-empty-icon">O</span>

            <h2>No orders yet</h2>

            <p>
                Create the first customer order to begin tracking sales,
                preparation and delivery.
            </p>

            <?php if ($canManageOrders): ?>
                <?= Html::a(
                    'Create order',
                    ['create'],
                    ['class' => 'btn btn-success'],
                ) ?>
            <?php endif; ?>
        </div>

    <?php else: ?>

        <div class="table-responsive orders-table-shell">
            <table class="table orders-table align-middle">

                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Status</th>
                        <th>Delivery</th>
                        <th>Total</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($orders as $order): ?>
                    <?php
                    $customerLabel = 'Unknown customer';
                    $customerType = 'Customer';

                    if ($order->company !== null) {
                        $customerLabel = (string) $order->company->name;
                        $customerType = 'Company';
                    } elseif ($order->contact !== null) {
                        $customerLabel = trim(
                            (string) $order->contact->first_name
                            . ' '
                            . (string) $order->contact->last_name,
                        );

                        $customerType = 'Individual';
                    }

                    $initial = strtoupper(
                        substr(trim($customerLabel), 0, 1),
                    );

                    $statusKey = strtolower(
                        str_replace(' ', '-', $order->status),
                    );

                    $deliveryType = 'none';
                    $deliveryLabel = 'Not scheduled';
                    $isOverdue = false;

                    if (!empty($order->delivery_date)) {
                        $deliveryTimestamp = strtotime(
                            $order->delivery_date,
                        );

                        $todayTimestamp = strtotime('today');

                        if (
                            $deliveryTimestamp < $todayTimestamp
                            && !in_array(
                                $order->status,
                                $finalStatuses,
                                true,
                            )
                        ) {
                            $deliveryType = 'overdue';
                            $deliveryLabel = 'Overdue';
                            $isOverdue = true;
                        } elseif ($deliveryTimestamp === $todayTimestamp) {
                            $deliveryType = 'today';
                            $deliveryLabel = 'Due today';
                        } else {
                            $deliveryType = 'upcoming';
                            $deliveryLabel = Yii::$app->formatter->asDate(
                                $order->delivery_date,
                            );
                        }
                    }

                    $searchValue = strtolower(
                        implode(
                            ' ',
                            [
                                (string) $order->id,
                                $customerLabel,
                                (string) $order->status,
                                (string) $order->delivery_date,
                            ],
                        ),
                    );

                    $itemCount = count($order->items);
                    ?>

                    <tr
                        class="<?= $isOverdue
                            ? 'orders-row-overdue'
                            : ''
                        ?>"
                        data-order-item
                        data-order-search="<?= Html::encode($searchValue) ?>"
                        data-order-status="<?= Html::encode(
                            strtolower($order->status),
                        ) ?>"
                        data-order-delivery="<?= Html::encode($deliveryType) ?>"
                    >
                        <td>
                            <div class="orders-number-cell">
                                <strong>
                                    #<?= Html::encode($order->id) ?>
                                </strong>

                                <small>
                                    Created
                                    <?= Html::encode(
                                        Yii::$app->formatter->asDate(
                                            $order->created_at,
                                        ),
                                    ) ?>
                                </small>
                            </div>
                        </td>

                        <td>
                            <div class="orders-customer-cell">
                                <span class="orders-customer-avatar">
                                    <?= Html::encode($initial ?: 'C') ?>
                                </span>

                                <div>
                                    <strong>
                                        <?= Html::encode($customerLabel) ?>
                                    </strong>

                                    <small>
                                        <?= Html::encode($customerType) ?>
                                    </small>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="orders-item-count">
                                <?= $itemCount ?>
                                item<?= $itemCount === 1 ? '' : 's' ?>
                            </span>
                        </td>

                        <td>
                            <span class="orders-status-badge orders-status-<?= Html::encode(
                                $statusKey,
                            ) ?>">
                                <?= Html::encode($order->status) ?>
                            </span>
                        </td>

                        <td>
                            <div class="orders-delivery-cell">
                                <strong class="<?= $isOverdue
                                    ? 'text-danger'
                                    : ''
                                ?>">
                                    <?= Html::encode($deliveryLabel) ?>
                                </strong>

                                <?php if (
                                    !empty($order->delivery_date)
                                    && $deliveryType !== 'upcoming'
                                ): ?>
                                    <small>
                                        <?= Html::encode(
                                            Yii::$app->formatter->asDate(
                                                $order->delivery_date,
                                            ),
                                        ) ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        </td>

                        <td>
                            <strong class="orders-total">
                                €<?= Html::encode(
                                    number_format(
                                        $order->getTotal(),
                                        2,
                                    ),
                                ) ?>
                            </strong>
                        </td>

                        <td class="text-end">
                            <div class="orders-row-actions">

                                <?= Html::a(
                                    'View',
                                    ['view', 'id' => $order->id],
                                    [
                                        'class' =>
                                            'btn btn-sm btn-outline-secondary',
                                    ],
                                ) ?>

                                <?= Html::a(
                                    'Invoice',
                                    ['invoice', 'id' => $order->id],
                                    [
                                        'class' =>
                                            'btn btn-sm btn-outline-secondary',
                                        'target' => '_blank',
                                        'rel' => 'noopener',
                                    ],
                                ) ?>

                                <?php if ($canManageOrders): ?>
                                    <?= Html::a(
                                        'Edit',
                                        ['update', 'id' => $order->id],
                                        [
                                            'class' =>
                                                'btn btn-sm btn-outline-success',
                                        ],
                                    ) ?>

                                    <?= Html::a(
                                        'Delete',
                                        ['delete', 'id' => $order->id],
                                        [
                                            'class' =>
                                                'btn btn-sm btn-outline-danger',
                                            'data' => [
                                                'confirm' =>
                                                    'Delete this order and its items?',
                                                'method' => 'post',
                                            ],
                                        ],
                                    ) ?>
                                <?php endif; ?>

                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>

            </table>
        </div>

        <div class="orders-filter-empty" data-order-empty hidden>
            No orders match the selected search and filters.
        </div>

    <?php endif; ?>

</section>
