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
)->label('Company (if this order is for a business)') ?>

<div class="mb-3">
    <button type="button" id="toggle-new-company" class="btn btn-outline-secondary btn-sm">
        + New Company
    </button>
</div>

<div id="new-company-fields" style="display:none; border: 1px solid #dedede; border-radius: 12px; padding: 16px; margin-bottom: 16px;">
    <p class="text-body-secondary small mb-3">
        Use this if the company doesn't have an account yet. A new company
        record will be created automatically for this order &mdash; this
        will be their first order in the system.
    </p>
    <div class="row g-2">
        <div class="col-md-6">
            <label class="form-label">Company Name</label>
            <input type="text" name="NewCompany[name]" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">City</label>
            <input type="text" name="NewCompany[city]" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">Country</label>
            <input type="text" name="NewCompany[country]" class="form-control">
        </div>
    </div>
</div>

<?= $form->field($model, 'customer_contact_id')->dropDownList(
    $contacts,
    ['prompt' => '-- No individual customer --']
)->label('Individual Customer (if not ordering as a company)') ?>

<div class="mb-3">
    <button type="button" id="toggle-new-customer" class="btn btn-outline-secondary btn-sm">
        + New Individual Customer
    </button>
</div>

<div id="new-customer-fields" style="display:none; border: 1px solid #dedede; border-radius: 12px; padding: 16px; margin-bottom: 16px;">
    <p class="text-body-secondary small mb-3">
        Use this if the customer doesn't have an account yet. A new contact
        record will be created automatically for this order.
    </p>
    <div class="row g-2">
        <div class="col-md-6">
            <label class="form-label">First Name</label>
            <input type="text" name="NewContact[first_name]" class="form-control" id="new-contact-first-name">
        </div>
        <div class="col-md-6">
            <label class="form-label">Last Name</label>
            <input type="text" name="NewContact[last_name]" class="form-control" id="new-contact-last-name">
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="NewContact[email]" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" name="NewContact[phone]" class="form-control">
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var toggleBtn = document.getElementById('toggle-new-customer');
    var fieldsBox = document.getElementById('new-customer-fields');
    if (toggleBtn && fieldsBox) {
        toggleBtn.addEventListener('click', function () {
            var isHidden = fieldsBox.style.display === 'none';
            fieldsBox.style.display = isHidden ? 'block' : 'none';
            toggleBtn.textContent = isHidden ? '− Cancel New Customer' : '+ New Individual Customer';
        });
    }

    var companyToggleBtn = document.getElementById('toggle-new-company');
    var companyFieldsBox = document.getElementById('new-company-fields');
    if (companyToggleBtn && companyFieldsBox) {
        companyToggleBtn.addEventListener('click', function () {
            var isHidden = companyFieldsBox.style.display === 'none';
            companyFieldsBox.style.display = isHidden ? 'block' : 'none';
            companyToggleBtn.textContent = isHidden ? '− Cancel New Company' : '+ New Company';
        });
    }
});
</script>

<?= $form->field($model, 'status')->dropDownList($statuses) ?>
<?= $form->field($model, 'delivery_date')->input('date') ?>
<?= $form->field($model, 'notes')->textarea() ?>

<h3>Order Items</h3>
<div id="order-items">
    <?php foreach ($itemModels as $index => $itemModel): ?>
        <div class="row order-item-row mb-2 align-items-center">
            <div class="col-md-4">
                <?= Html::activeDropDownList($itemModel, "[$index]product_id", $productOptions, [
                    'class' => 'form-control product-select',
                    'prompt' => '-- Select product --',
                ]) ?>
            </div>
            <div class="col-md-2">
                <?= Html::activeTextInput($itemModel, "[$index]quantity", ['class' => 'form-control qty-input', 'placeholder' => 'Quantity']) ?>
            </div>
            <div class="col-md-2">
                <?= Html::activeTextInput($itemModel, "[$index]unit_price", ['class' => 'form-control unit-price-input', 'placeholder' =>'Unit Price']) ?>
            </div>
            <div class="col-md-2">
                <span class="row-subtotal fw-semibold">&euro;0.00</span>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-item-row">Remove</button>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<button type="button" id="add-item-row" class="btn btn-secondary mb-3">Add Item</button>

<div class="d-flex justify-content-end mb-3">
    <div style="min-width: 220px;" class="text-end">
        <span class="fs-5 fw-bold">Grand Total: <span id="order-grand-total">&euro;0.00</span></span>
    </div>
</div>

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

    function formatEuro(n) {
        return '\u20ac' + (isNaN(n) ? '0.00' : n.toFixed(2));
    }

    function recalcRow(row) {
        var qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        var price = parseFloat(row.querySelector('.unit-price-input').value) || 0;
        var subtotal = qty * price;
        row.querySelector('.row-subtotal').textContent = formatEuro(subtotal);
        return subtotal;
    }

    function recalcGrandTotal() {
        var total = 0;
        document.querySelectorAll('.order-item-row').forEach(function (row) {
            total += recalcRow(row);
        });
        document.getElementById('order-grand-total').textContent = formatEuro(total);
    }

    function attachRowListeners(row) {
        row.querySelector('.qty-input').addEventListener('input', recalcGrandTotal);
        row.querySelector('.unit-price-input').addEventListener('input', recalcGrandTotal);
    }

    function attachPriceFill(selectEl, priceInputEl) {
        selectEl.addEventListener('change', function () {
            var price = productPrices[selectEl.value];
            if (price !== undefined) {
                priceInputEl.value = price;
            }
            recalcGrandTotal();
        });
    }

    document.querySelectorAll('.order-item-row').forEach(function (row) {
        var select = row.querySelector('.product-select');
        var priceInput = row.querySelector('.unit-price-input');
        attachPriceFill(select, priceInput);
        attachRowListeners(row);
    });

    addButton.addEventListener('click', function () {
        var row = document.createElement('div');
        row.className = 'row order-item-row mb-2 align-items-center';
        row.innerHTML =
            '<div class="col-md-4"><select name="OrderItem[' + rowIndex + '][product_id]" class="form-control product-select">' + productOptionsHtml + '</select></div>' +
            '<div class="col-md-2"><input type="text" name="OrderItem[' + rowIndex + '][quantity]" class="form-control qty-input" placeholder="Quantity"></div>' +
            '<div class="col-md-2"><input type="text" name="OrderItem[' + rowIndex + '][unit_price]" class="form-control unit-price-input" placeholder="Unit Price"></div>' +
            '<div class="col-md-2"><span class="row-subtotal fw-semibold">\u20ac0.00</span></div>' +
            '<div class="col-md-2"><button type="button" class="btn btn-danger remove-item-row">Remove</button></div>';
        container.appendChild(row);
        var select = row.querySelector('.product-select');
        var priceInput = row.querySelector('.unit-price-input');
        attachPriceFill(select, priceInput);
        attachRowListeners(row);
        rowIndex++;
    });

    container.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-item-row')) {
            e.target.closest('.order-item-row').remove();
            recalcGrandTotal();
        }
    });

    recalcGrandTotal();
});
</script>
