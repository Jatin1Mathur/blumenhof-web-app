<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\CustomerCompany $model */

$this->title = $model->name;
?>
<h1><?= Html::encode($this->title) ?></h1>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'name',
        [
            'label' => 'Category',
            'value' => $model->category ? $model->category->name : '-',
        ],
        'address', 'city', 'postal_code', 'country', 'phone', 'email', 'notes',
    ],
]) ?>
