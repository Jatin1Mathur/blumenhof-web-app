<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\ProductionOrder $model */

$this->title = 'Edit Production Task #' . $model->id;

$statuses = array_combine(common\models\ProductionOrder::statusList(), common\models\ProductionOrder::statusList());
$sources = array_combine(common\models\ProductionOrder::sourceList(), common\models\ProductionOrder::sourceList());
?>
<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'status')->dropDownList($statuses) ?>
<?= $form->field($model, 'source')->dropDownList($sources) ?>
<?= $form->field($model, 'batch_date')->input('date') ?>
<?= $form->field($model, 'notes')->textarea() ?>

<div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
