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
        [
            'label' => 'Stock Quantity',
            'value' => $model->stock ? $model->stock->quantity : 'No stock record',
        ],
        [
            'label' => 'Low Stock Threshold',
            'value' => $model->stock ? $model->stock->low_stock_threshold : '-',
        ],
        [
            'label' => 'Stock Status',
            'value' => $model->stock
                ? ($model->stock->isLowStock() ? 'Low Stock' : 'OK')
                : '-',
        ],
        [
            'label' => 'Expiry Date',
            'value' => $model->stock ? ($model->stock->expiry_date ?? '-') : '-',
        ],
        'description:ntext',
    ],
]) ?>
