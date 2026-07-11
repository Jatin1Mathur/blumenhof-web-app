<?php

declare(strict_types=1);

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ProductionOrder[] $productionOrders */

$this->title = 'Production Tasks';

$statusBadgeMap = [
    'Pending' => 'secondary',
    'In Progress' => 'warning',
    'Done' => 'success',
];
$sourceBadgeMap = [
    'stock' => 'info',
    'produce' => 'warning',
];
?>
<div class="module-page-header">
    <div>
        <h1><?= Html::encode($this->title) ?></h1>
        <p class="text-body-secondary mb-0">Track production tasks from stock decision through completion.</p>
    </div>
</div>

<div class="table-responsive module-table-wrap">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Order</th>
                <th>Status</th>
                <th>Source</th>
                <th>Batch Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($productionOrders as $po): ?>
            <?php
            $statusBadge = $statusBadgeMap[$po->status] ?? 'secondary';
            $sourceBadge = $sourceBadgeMap[$po->source] ?? 'secondary';
            ?>
            <tr>
                <td class="text-body-secondary">#<?= Html::encode($po->id) ?></td>
                <td><?= Html::a('Order #' . $po->order_id, ['order/view', 'id' => $po->order_id], ['class' => 'fw-semibold']) ?></td>
                <td><span class="badge text-bg-<?= $statusBadge ?> module-status-badge"><?= Html::encode($po->status) ?></span></td>
                <td><span class="badge text-bg-<?= $sourceBadge ?> module-status-badge"><?= Html::encode($po->source) ?></span></td>
                <td><?= Html::encode($po->batch_date ?? '-') ?></td>
                <td>
                    <?= Html::a('View', ['view', 'id' => $po->id], ['class' => 'btn btn-sm btn-outline-secondary']) ?>
                    <?= Html::a('Edit', ['update', 'id' => $po->id], ['class' => 'btn btn-sm btn-outline-secondary']) ?>
                    <?= Html::a('Delete', ['delete', 'id' => $po->id], [
                        'class' => 'btn btn-sm btn-outline-danger',
                        'data' => ['confirm' => 'Delete this production task?', 'method' => 'post'],
                    ]) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
