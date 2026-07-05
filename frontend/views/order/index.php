<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Order[] $orders */

$this->title = 'Orders';
?>
<h1><?= Html::encode($this->title) ?></h1>

<p><?= Html::a('New Order', ['create'], ['class' => 'btn btn-primary']) ?></p>

<table class="table">
    <thead>
        <tr><th>ID</th><th>Customer</th><th>Status</th><th>Delivery Date</th><th>Total</th><th>Actions</th></tr>
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
        ?>
        <tr>
            <td><?= Html::encode($order->id) ?></td>
            <td><?= Html::encode($customerLabel) ?></td>
            <td><?= Html::encode($order->status) ?></td>
            <td><?= Html::encode($order->delivery_date ?? '-') ?></td>
            <td><?= Html::encode(number_format($order->getTotal(), 2)) ?></td>
            <td>
                <?= Html::a('View', ['view', 'id' => $order->id]) ?> |
                <?= Html::a('Edit', ['update', 'id' => $order->id]) ?> |
                <?= Html::a('Delete', ['delete', 'id' => $order->id], [
                    'data' => ['confirm' => 'Delete this order?', 'method' => 'post'],
                ]) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
