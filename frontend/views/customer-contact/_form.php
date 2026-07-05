<?php

use common\models\CustomerCompany;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\CustomerContact $model */
/** @var yii\widgets\ActiveForm $form */

$companies = ArrayHelper::map(CustomerCompany::find()->all(), 'id', 'name');
?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'customer_company_id')->dropDownList(
    $companies,
    ['prompt' => '-- No company (individual customer) --']
) ?>
<?= $form->field($model, 'first_name') ?>
<?= $form->field($model, 'last_name') ?>
<?= $form->field($model, 'email') ?>
<?= $form->field($model, 'phone') ?>
<?= $form->field($model, 'role_title') ?>
<?= $form->field($model, 'notes')->textarea() ?>

<div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
