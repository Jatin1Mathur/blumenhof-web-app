<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Order $model */

$this->title = 'Order #' . $model->id;
?>
<h1><?= Html::encode($this->title) ?></h1>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success"><?= Html::encode(Yii::$app->session->getFlash('success')) ?></div>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger"><?= Html::encode(Yii::$app->session->getFlash('error')) ?></div>
<?php endif; ?>

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

<h3>Change Status</h3>
<?php $allowedStatuses = $model->getAllowedNextStatuses(); ?>
<?php if (empty($allowedStatuses)): ?>
    <p class="text-muted">This order is in its final status and cannot be changed further.</p>
<?php else: ?>
    <?php foreach ($allowedStatuses as $nextStatus): ?>
        <?= Html::beginForm(['change-status', 'id' => $model->id], 'post', ['style' => 'display:inline-block; margin-right: 8px;']) ?>
            <?= Html::hiddenInput('status', $nextStatus) ?>
            <?= Html::submitButton('Move to: ' . Html::encode($nextStatus), ['class' => 'btn btn-outline-primary']) ?>
        <?= Html::endForm() ?>
    <?php endforeach; ?>
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
