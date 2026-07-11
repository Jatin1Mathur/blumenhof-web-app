<?php

declare(strict_types=1);

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Order[] $orders */

$this->title = 'Orders';

$statusBadgeMap = [
    'Draft' => 'secondary',
    'Confirmed' => 'primary',
    'In Preparation' => 'warning',
    'Ready' => 'info',
    'Delivered' => 'success',
    'Completed' => 'success',
];
?>
<div class="module-page-header">
    <div>
        <h1><?= Html::encode($this->title) ?></h1>
        <p class="text-body-secondary mb-0">Track every order from draft to delivery.</p>
    </div>
    <?= Html::a('+ New Order', ['create'], ['class' => 'btn btn-success']) ?>
</div>

<div class="table-responsive module-table-wrap">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Delivery Date</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $order): ?>
            <?php
            $customerLabel = '-';
            if ($order->company) {
                $customerLabel = $order->company->name;
            } elseif ($order->contact) {
                $customerLabel = $order->contact->first_name . ' ' . $order->contact->last_name;
            }
            $badgeClass = $statusBadgeMap[$order->status] ?? 'secondary';
            ?>
            <tr>
                <td class="text-body-secondary">#<?= Html::encode($order->id) ?></td>
                <td class="fw-semibold"><?= Html::encode($customerLabel) ?></td>
                <td><span class="badge text-bg-<?= $badgeClass ?> module-status-badge"><?= Html::encode($order->status) ?></span></td>
                <td><?= Html::encode($order->delivery_date ?? '-') ?></td>
                <td class="fw-semibold">&euro;<?= Html::encode(number_format($order->getTotal(), 2)) ?></td>
                <td>
                    <?= Html::a('View', ['view', 'id' => $order->id], ['class' => 'btn btn-sm btn-outline-secondary']) ?>
                    <?= Html::a('Edit', ['update', 'id' => $order->id], ['class' => 'btn btn-sm btn-outline-secondary']) ?>
                    <?= Html::a('Delete', ['delete', 'id' => $order->id], [
                        'class' => 'btn btn-sm btn-outline-danger',
                        'data' => ['confirm' => 'Delete this order?', 'method' => 'post'],
                    ]) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
