<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\CustomerContact $model */

$this->title = $model->first_name . ' ' . $model->last_name;
?>
<h1><?= Html::encode($this->title) ?></h1>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'first_name',
        'last_name',
        [
            'label' => 'Company',
            'value' => $model->company ? $model->company->name : '-',
        ],
        'email',
        'phone',
        'role_title',
        'notes',
    ],
]) ?>
