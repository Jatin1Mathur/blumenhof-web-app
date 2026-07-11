<?php

declare(strict_types=1);

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\InventoryStock[] $stocks */

$this->title = 'Inventory Stock';
?>
<div class="module-page-header">
    <div>
        <h1><?= Html::encode($this->title) ?></h1>
        <p class="text-body-secondary mb-0">Monitor stock levels and get warned before anything runs low.</p>
    </div>
    <?= Html::a('+ New Stock Record', ['create'], ['class' => 'btn btn-success']) ?>
</div>

<div class="table-responsive module-table-wrap">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Threshold</th>
                <th>Expiry</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($stocks as $stock): ?>
            <?php $isLow = $stock->isLowStock(); ?>
            <tr class="<?= $isLow ? 'module-row-low-stock' : '' ?>">
                <td class="fw-semibold"><?= $stock->product ? Html::encode($stock->product->name) : '-' ?></td>
                <td><?= Html::encode($stock->quantity) ?></td>
                <td><?= Html::encode($stock->low_stock_threshold) ?></td>
                <td><?= Html::encode($stock->expiry_date ?? '-') ?></td>
                <td>
                    <?php if ($isLow): ?>
                        <span class="badge text-bg-danger module-status-badge">Low Stock</span>
                    <?php else: ?>
                        <span class="badge text-bg-success module-status-badge">OK</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?= Html::a('Edit', ['update', 'id' => $stock->id], ['class' => 'btn btn-sm btn-outline-secondary']) ?>
                    <?= Html::a('Delete', ['delete', 'id' => $stock->id], [
                        'class' => 'btn btn-sm btn-outline-danger',
                        'data' => ['confirm' => 'Delete this stock record?', 'method' => 'post'],
                    ]) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
