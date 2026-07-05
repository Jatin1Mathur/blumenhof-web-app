<?php

use common\models\CustomerCategory;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\CustomerCompany $model */
/** @var yii\widgets\ActiveForm $form */

$categories = ArrayHelper::map(CustomerCategory::find()->all(), 'id', 'name');
?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'customer_category_id')->dropDownList(
    $categories,
    ['prompt' => '-- Select category --']
) ?>
<?= $form->field($model, 'address') ?>
<?= $form->field($model, 'city') ?>
<?= $form->field($model, 'postal_code') ?>
<?= $form->field($model, 'country') ?>
<?= $form->field($model, 'phone') ?>
<?= $form->field($model, 'email') ?>
<?= $form->field($model, 'notes')->textarea() ?>

<div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
