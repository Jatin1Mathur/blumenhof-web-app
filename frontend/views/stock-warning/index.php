<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\InventoryStock[] $warnings */

$this->title = 'Stock Warnings';
?>
<h1><?= Html::encode($this->title) ?></h1>

<?php if (empty($warnings)): ?>
    <div class="alert alert-success">No stock warnings. Everything looks good.</div>
<?php else: ?>
    <table class="table">
        <thead>
            <tr><th>Product</th><th>Quantity</th><th>Threshold</th><th>Expiry</th><th>Warning</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php foreach ($warnings as $stock): ?>
            <tr class="<?= $stock->isExpired() ? 'table-danger' : 'table-warning' ?>">
                <td><?= $stock->product ? Html::encode($stock->product->name) : '-' ?></td>
                <td><?= Html::encode($stock->quantity) ?></td>
                <td><?= Html::encode($stock->low_stock_threshold) ?></td>
                <td><?= Html::encode($stock->expiry_date ?? '-') ?></td>
                <td>
                    <?php
                    $reasons = [];
                    if ($stock->isLowStock()) {
                        $reasons[] = 'Low stock';
                    }
                    if ($stock->isExpired()) {
                        $reasons[] = 'Expired';
                    } elseif ($stock->isExpiringSoon()) {
                        $reasons[] = 'Expiring soon';
                    }
                    echo Html::encode(implode(', ', $reasons));
                    ?>
                </td>
                <td>
                    <?= Html::a('Edit', ['inventory-stock/update', 'id' => $stock->id]) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
