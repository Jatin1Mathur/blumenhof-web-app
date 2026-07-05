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
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'product_category_id')->dropDownList(
    $categories,
    ['prompt' => '-- Select category --']
) ?>
<?= $form->field($model, 'price') ?>
<?= $form->field($model, 'is_perishable')->checkbox() ?>
<?= $form->field($model, 'description')->textarea() ?>
<?= $form->field($model, 'imageFile')->fileInput() ?>

<?php if (!$model->isNewRecord && $model->image_path): ?>
    <div class="form-group">
        <label>Current image:</label><br>
        <img src="<?= Html::encode(Yii::getAlias('@web/' . $model->image_path)) ?>" style="max-width:200px;" alt="Product image">
    </div>
<?php endif; ?>

<div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
