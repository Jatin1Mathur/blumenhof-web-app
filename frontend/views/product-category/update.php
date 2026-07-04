<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ProductCategory $model */

$this->title = 'Edit: ' . $model->name;
?>
<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', ['model' => $model]) ?>
