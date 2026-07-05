<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Product $model */

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
        'price',
        [
            'label' => 'Perishable',
            'value' => $model->is_perishable ? 'Yes' : 'No',
        ],
        'description:ntext',
    ],
]) ?>
