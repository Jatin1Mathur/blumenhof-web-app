<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\ProductionOrder $model */

$this->title = 'Production Task #' . $model->id;
?>
<h1><?= Html::encode($this->title) ?></h1>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success"><?= Html::encode(Yii::$app->session->getFlash('success')) ?></div>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger"><?= Html::encode(Yii::$app->session->getFlash('error')) ?></div>
<?php endif; ?>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'label' => 'Order',
            'value' => 'Order #' . $model->order_id,
        ],
        'status',
        'source',
        [
            'label' => 'Batch Date',
            'value' => $model->batch_date ?? '-',
        ],
        [
            'label' => 'Assignee',
            'value' => $model->assignee ? $model->assignee->username : '-',
        ],
        'notes:ntext',
    ],
]) ?>

<p><?= Html::a('Back to Order', ['order/view', 'id' => $model->order_id]) ?></p>
