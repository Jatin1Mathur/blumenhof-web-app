<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CustomerContact $model */

$this->title = 'New Contact';
?>
<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', ['model' => $model]) ?>
