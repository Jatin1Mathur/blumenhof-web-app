<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Order $model */

$this->title = 'Order #' . $model->id;

$customerLabel = '-';
if ($model->company) {
    $customerLabel = $model->company->name;
} elseif ($model->contact) {
    $customerLabel = $model->contact->first_name . ' ' . $model->contact->last_name;
}
?>
<h1><?= Html::encode($this->title) ?></h1>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'label' => 'Customer',
            'value' => $customerLabel,
        ],
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

<h3>Items</h3>
<table class="table">
    <thead>
        <tr><th>Product</th><th>Quantity</th><th>Unit Price</th><th>Subtotal</th></tr>
    </thead>
    <tbody>
    <?php foreach ($model->items as $item): ?>
        <tr>
            <td><?= $item->product ? Html::encode($item->product->name) : '-' ?></td>
            <td><?= Html::encode($item->quantity) ?></td>
            <td><?= Html::encode(number_format((float) $item->unit_price, 2)) ?></td>
            <td><?= Html::encode(number_format($item->getSubtotal(), 2)) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
