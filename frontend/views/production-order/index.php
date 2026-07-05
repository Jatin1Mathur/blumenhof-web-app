<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ProductionOrder[] $productionOrders */

$this->title = 'Production Tasks';
?>
<h1><?= Html::encode($this->title) ?></h1>

<table class="table">
    <thead>
        <tr><th>ID</th><th>Order</th><th>Status</th><th>Source</th><th>Batch Date</th><th>Actions</th></tr>
    </thead>
    <tbody>
    <?php foreach ($productionOrders as $po): ?>
        <tr>
            <td><?= Html::encode($po->id) ?></td>
            <td><?= Html::a('Order #' . $po->order_id, ['order/view', 'id' => $po->order_id]) ?></td>
            <td><?= Html::encode($po->status) ?></td>
            <td><?= Html::encode($po->source) ?></td>
            <td><?= Html::encode($po->batch_date ?? '-') ?></td>
            <td>
                <?= Html::a('View', ['view', 'id' => $po->id]) ?> |
                <?= Html::a('Edit', ['update', 'id' => $po->id]) ?> |
                <?= Html::a('Delete', ['delete', 'id' => $po->id], [
                    'data' => ['confirm' => 'Delete this production task?', 'method' => 'post'],
                ]) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
