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

<h3>Change Status</h3>
<?php $allowedStatuses = $model->getAllowedNextStatuses(); ?>
<?php if (empty($allowedStatuses)): ?>
    <p class="text-muted">This task is done and cannot be changed further.</p>
<?php else: ?>
    <?php foreach ($allowedStatuses as $nextStatus): ?>
        <?= Html::beginForm(['change-status', 'id' => $model->id], 'post', ['style' => 'display:inline-block; margin-right: 8px;']) ?>
            <?= Html::hiddenInput('status', $nextStatus) ?>
            <?= Html::submitButton('Move to: ' . Html::encode($nextStatus), ['class' => 'btn btn-outline-primary']) ?>
        <?= Html::endForm() ?>
    <?php endforeach; ?>
<?php endif; ?>

<p><?= Html::a('Back to Order', ['order/view', 'id' => $model->order_id]) ?></p>
