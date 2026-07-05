<?php

use common\models\ProductCategory;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Product $model */
/** @var yii\widgets\ActiveForm $form */

$categories = ArrayHelper::map(ProductCategory::find()->all(), 'id', 'name');
?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'product_category_id')->dropDownList(
    $categories,
    ['prompt' => '-- Select category --']
) ?>
<?= $form->field($model, 'price') ?>
<?= $form->field($model, 'is_perishable')->checkbox() ?>
<?= $form->field($model, 'description')->textarea() ?>

<div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
