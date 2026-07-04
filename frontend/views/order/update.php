<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Order $model */
/** @var common\models\OrderItem[] $itemModels */

$this->title = 'Edit Order #' . $model->id;
?>
<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', ['model' => $model, 'itemModels' => $itemModels]) ?>
