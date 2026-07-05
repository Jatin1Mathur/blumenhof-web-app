<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Order $model */
/** @var common\models\OrderItem[] $itemModels */

$statuses = array_combine(common\models\Order::statusList(), common\models\Order::statusList());
?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'customer_company_id')->textInput() ?>
<?= $form->field($model, 'customer_contact_id')->textInput() ?>
<?= $form->field($model, 'status')->dropDownList($statuses) ?>
<?= $form->field($model, 'delivery_date')->input('date') ?>
<?= $form->field($model, 'notes')->textarea() ?>

<h3>Order Items</h3>
<div id="order-items">
    <?php foreach ($itemModels as $index => $itemModel): ?>
        <div class="row order-item-row mb-2">
            <div class="col-md-4">
                <?= Html::activeTextInput($itemModel, "[$index]product_id", ['class' => 'form-control', 'placeholder' => 'Product ID']) ?>
            </div>
            <div class="col-md-3">
                <?= Html::activeTextInput($itemModel, "[$index]quantity", ['class' => 'form-control', 'placeholder' => 'Quantity']) ?>
            </div>
            <div class="col-md-3">
                <?= Html::activeTextInput($itemModel, "[$index]unit_price", ['class' => 'form-control', 'placeholder' => 'Unit Price']) ?>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-item-row">Remove</button>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<button type="button" id="add-item-row" class="btn btn-secondary mb-3">Add Item</button>

<div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var container = document.getElementById('order-items');
    var addButton = document.getElementById('add-item-row');
    var rowIndex = <?= count($itemModels) ?>;

    addButton.addEventListener('click', function () {
        var row = document.createElement('div');
        row.className = 'row order-item-row mb-2';
        row.innerHTML =
            '<div class="col-md-4"><input type="text" name="OrderItem[' + rowIndex + '][product_id]" class="form-control" placeholder="Product ID"></div>' +
            '<div class="col-md-3"><input type="text" name="OrderItem[' + rowIndex + '][quantity]" class="form-control" placeholder="Quantity"></div>' +
            '<div class="col-md-3"><input type="text" name="OrderItem[' + rowIndex + '][unit_price]" class="form-control" placeholder="Unit Price"></div>' +
            '<div class="col-md-2"><button type="button" class="btn btn-danger remove-item-row">Remove</button></div>';
        container.appendChild(row);
        rowIndex++;
    });

    container.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-item-row')) {
            e.target.closest('.order-item-row').remove();
        }
    });
});
</script>
