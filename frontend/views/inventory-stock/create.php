<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\InventoryStock $model */

$this->title = 'New Stock Record';
?>
<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', ['model' => $model]) ?>
