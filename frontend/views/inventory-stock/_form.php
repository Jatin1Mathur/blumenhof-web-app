<?php

use common\models\Product;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\InventoryStock $model */
/** @var yii\widgets\ActiveForm $form */

$products = ArrayHelper::map(Product::find()->all(), 'id', 'name');
?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'product_id')->dropDownList(
    $products,
    ['prompt' => '-- Select product --']
) ?>
<?= $form->field($model, 'quantity') ?>
<?= $form->field($model, 'low_stock_threshold') ?>
<?= $form->field($model, 'expiry_date')->input('date') ?>

<div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
