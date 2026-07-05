<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ProductCategory $model */

$this->title = 'New Category';
?>
<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', ['model' => $model]) ?>
