<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\InventoryStock[] $stocks */

$this->title = 'Inventory Stock';
?>
<h1><?= Html::encode($this->title) ?></h1>

<p><?= Html::a('New Stock Record', ['create'], ['class' => 'btn btn-primary']) ?></p>

<table class="table">
    <thead>
        <tr><th>Product</th><th>Quantity</th><th>Threshold</th><th>Expiry</th><th>Status</th><th>Actions</th></tr>
    </thead>
    <tbody>
    <?php foreach ($stocks as $stock): ?>
        <tr class="<?= $stock->isLowStock() ? 'table-danger' : '' ?>">
            <td><?= $stock->product ? Html::encode($stock->product->name) : '-' ?></td>
            <td><?= Html::encode($stock->quantity) ?></td>
            <td><?= Html::encode($stock->low_stock_threshold) ?></td>
            <td><?= Html::encode($stock->expiry_date ?? '-') ?></td>
            <td><?= $stock->isLowStock() ? '<span class="badge bg-danger">Low Stock</span>' : '<span class="badge bg-success">OK</span>' ?></td>
            <td>
                <?= Html::a('Edit', ['update', 'id' => $stock->id]) ?> |
                <?= Html::a('Delete', ['delete', 'id' => $stock->id], [
                    'data' => ['confirm' => 'Delete this stock record?', 'method' => 'post'],
                ]) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
