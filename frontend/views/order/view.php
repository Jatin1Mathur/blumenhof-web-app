<?php

use common\models\ProductionOrder;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Order $model */

$this->title = 'Order #' . $model->id;

$productionOrder = ProductionOrder::findOne(['order_id' => $model->id]);
?>
<h1><?= Html::encode($this->title) ?></h1>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'status',
        [
            'label' => 'Delivery Date',
            'value' => $model->delivery_date ?? '-',
        ],
        'notes:ntext',
        [
            'label' => 'Total',
            'value' => number_format($model->getTotal(), 2),
        ],
    ],
]) ?>

<h3>Production</h3>
<?php if ($productionOrder !== null): ?>
    <p>
        Production task already exists:
        <?= Html::a('View Production Task #' . $productionOrder->id, ['production-order/view', 'id' => $productionOrder->id]) ?>
        (Status: <?= Html::encode($productionOrder->status) ?>)
    </p>
<?php elseif ($model->status === \common\models\Order::STATUS_CONFIRMED): ?>
    <?= Html::a('Generate Production Task', ['production-order/generate', 'orderId' => $model->id], [
        'class' => 'btn btn-primary',
        'data' => ['method' => 'post', 'confirm' => 'Generate a production task for this order?'],
    ]) ?>
<?php else: ?>
    <p class="text-muted">Order must be Confirmed before a production task can be generated.</p>
<?php endif; ?>

<h3>Items</h3>
<table class="table">
    <thead>
        <tr><th>Product ID</th><th>Quantity</th><th>Unit Price</th><th>Subtotal</th></tr>
    </thead>
    <tbody>
    <?php foreach ($model->items as $item): ?>
        <tr>
            <td><?= Html::encode($item->product_id) ?></td>
            <td><?= Html::encode($item->quantity) ?></td>
            <td><?= Html::encode(number_format((float) $item->unit_price, 2)) ?></td>
            <td><?= Html::encode(number_format($item->getSubtotal(), 2)) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
