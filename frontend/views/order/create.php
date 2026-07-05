<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Order $model */
/** @var common\models\OrderItem[] $itemModels */

$this->title = 'New Order';
?>
<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', ['model' => $model, 'itemModels' => $itemModels]) ?>
