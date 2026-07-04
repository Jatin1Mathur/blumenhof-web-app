<?php

use common\models\CustomerCompany;
use common\models\CustomerContact;
use common\models\Product;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Order $model */
/** @var common\models\OrderItem[] $itemModels */

$statuses = array_combine(common\models\Order::statusList(), common\models\Order::statusList());

$companies = ArrayHelper::map(CustomerCompany::find()->all(), 'id', 'name');
$contacts = ArrayHelper::map(CustomerContact::find()->all(), 'id', function ($c) {
    return $c->first_name . ' ' . $c->last_name;
});
$products = Product::find()->all();
$productOptions = ArrayHelper::map($products, 'id', 'name');
$productPrices = ArrayHelper::map($products, 'id', 'price');
?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'customer_company_id')->dropDownList(
    $companies,
    ['prompt' => '-- No company --']
) ?>
<?= $form->field($model, 'customer_contact_id')->dropDownList(
    $contacts,
    ['prompt' => '-- No contact --']
) ?>
<?= $form->field($model, 'status')->dropDownList($statuses) ?>
<?= $form->field($model, 'delivery_date')->input('date') ?>
<?= $form->field($model, 'notes')->textarea() ?>

<h3>Order Items</h3>
<div id="order-items">
    <?php foreach ($itemModels as $index => $itemModel): ?>
        <div class="row order-item-row mb-2">
            <div class="col-md-4">
                <?= Html::activeDropDownList($itemModel, "[$index]product_id", $productOptions, [
                    'class' => 'form-control product-select',
                    'prompt' => '-- Select product --',
                ]) ?>
            </div>
            <div class="col-md-3">
                <?= Html::activeTextInput($itemModel, "[$index]quantity", ['class' => 'form-control', 'placeholder' => 'Quantity']) ?>
            </div>
            <div class="col-md-3">
                <?= Html::activeTextInput($itemModel, "[$index]unit_price", ['class' => 'form-control unit-price-input', 'placeholder' => 'Unit Price']) ?>
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
    var productPrices = <?= Json::encode($productPrices) ?>;
    var productOptionsHtml = <?= Json::encode(
        '<option value="">-- Select product --</option>' . implode('', array_map(
            static fn ($id, $name) => '<option value="' . $id . '">' . Html::encode($name) . '</option>',
            array_keys($productOptions),
            $productOptions
        ))
    ) ?>;

    function attachPriceFill(selectEl, priceInputEl) {
        selectEl.addEventListener('change', function () {
            var price = productPrices[selectEl.value];
            if (price !== undefined) {
                priceInputEl.value = price;
            }
        });
    }

    document.querySelectorAll('.product-select').forEach(function (select) {
        var row = select.closest('.order-item-row');
        var priceInput = row.querySelector('.unit-price-input');
        attachPriceFill(select, priceInput);
    });

    addButton.addEventListener('click', function () {
        var row = document.createElement('div');
        row.className = 'row order-item-row mb-2';
        row.innerHTML =
            '<div class="col-md-4"><select name="OrderItem[' + rowIndex + '][product_id]" class="form-control product-select">' + productOptionsHtml + '</select></div>' +
            '<div class="col-md-3"><input type="text" name="OrderItem[' + rowIndex + '][quantity]" class="form-control" placeholder="Quantity"></div>' +
            '<div class="col-md-3"><input type="text" name="OrderItem[' + rowIndex + '][unit_price]" class="form-control unit-price-input" placeholder="Unit Price"></div>' +
            '<div class="col-md-2"><button type="button" class="btn btn-danger remove-item-row">Remove</button></div>';
        container.appendChild(row);

        var select = row.querySelector('.product-select');
        var priceInput = row.querySelector('.unit-price-input');
        attachPriceFill(select, priceInput);

        rowIndex++;
    });

    container.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-item-row')) {
            e.target.closest('.order-item-row').remove();
        }
    });
});
</script>
