<?php

declare(strict_types=1);

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Order $model */

$this->title = 'Invoice — Order #' . $model->id;

$customerLabel = '-';
$customerAddress = '';
if ($model->company) {
    $customerLabel = $model->company->name;
    $customerAddress = trim(($model->company->city ?? '') . ' ' . ($model->company->country ?? ''));
} elseif ($model->contact) {
    $customerLabel = $model->contact->first_name . ' ' . $model->contact->last_name;
}
?>
<div style="max-width: 800px; margin: 0 auto;">

    <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px solid #9acb31; padding-bottom: 20px; margin-bottom: 30px;">
        <div>
            <?= Html::img(Yii::getAlias('@webroot/images/blumenhof-logo.svg'), ['alt' => 'Blumenhof', 'style' => 'max-height: 70px;']) ?>
        </div>
        <div style="text-align: right;">
            <h1 style="margin: 0; font-size: 28px; color: #4a6b1a;">INVOICE</h1>
            <p style="margin: 4px 0 0; color: #737373;">Invoice #<?= Html::encode($model->id) ?></p>
            <p style="margin: 0; color: #737373;">Date: <?= Html::encode(date('Y-m-d')) ?></p>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; margin-bottom: 30px;">
        <div>
            <p style="margin: 0; font-weight: 700; color: #737373; text-transform: uppercase; font-size: 12px; letter-spacing: 1px;">Billed To</p>
            <p style="margin: 4px 0 0; font-size: 16px; font-weight: 600;"><?= Html::encode($customerLabel) ?></p>
            <?php if ($customerAddress): ?>
                <p style="margin: 0; color: #737373;"><?= Html::encode($customerAddress) ?></p>
            <?php endif; ?>
        </div>
        <div style="text-align: right;">
            <p style="margin: 0; font-weight: 700; color: #737373; text-transform: uppercase; font-size: 12px; letter-spacing: 1px;">Status</p>
            <p style="margin: 4px 0 0; font-size: 16px; font-weight: 600;"><?= Html::encode($model->status) ?></p>
            <?php if ($model->delivery_date): ?>
                <p style="margin: 0; color: #737373;">Delivery: <?= Html::encode($model->delivery_date) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
        <thead>
            <tr style="background: #f4f4f4;">
                <th style="text-align: left; padding: 10px; border-bottom: 2px solid #dedede;">Product</th>
                <th style="text-align: right; padding: 10px; border-bottom: 2px solid #dedede;">Quantity</th>
                <th style="text-align: right; padding: 10px; border-bottom: 2px solid #dedede;">Unit Price</th>
                <th style="text-align: right; padding: 10px; border-bottom: 2px solid #dedede;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($model->items as $item): ?>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee;"><?= $item->product ? Html::encode($item->product->name) : '-' ?></td>
                <td style="text-align: right; padding: 10px; border-bottom: 1px solid #eee;"><?= Html::encode($item->quantity) ?></td>
                <td style="text-align: right; padding: 10px; border-bottom: 1px solid #eee;">&euro;<?= Html::encode(number_format((float) $item->unit_price, 2)) ?></td>
                <td style="text-align: right; padding: 10px; border-bottom: 1px solid #eee;">&euro;<?= Html::encode(number_format($item->getSubtotal(), 2)) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div style="display: flex; justify-content: flex-end; margin-bottom: 40px;">
        <div style="width: 250px;">
            <div style="display: flex; justify-content: space-between; padding: 8px 0; font-size: 20px; font-weight: 800; border-top: 2px solid #202020;">
                <span>Total</span>
                <span>&euro;<?= Html::encode(number_format($model->getTotal(), 2)) ?></span>
            </div>
        </div>
    </div>

    <p style="text-align: center; color: #737373; font-size: 12px; border-top: 1px solid #dedede; padding-top: 20px;">
        blumenHof &middot; Internal florist management platform &middot; Thank you for your business.
    </p>

    <div class="invoice-print-btn" style="text-align: center; margin-top: 24px;">
        <button onclick="window.print()" style="background: #6f9f1f; color: #fff; border: none; padding: 12px 28px; border-radius: 10px; font-weight: 700; cursor: pointer;">
            🖨 Print / Save as PDF
        </button>
    </div>

</div>
