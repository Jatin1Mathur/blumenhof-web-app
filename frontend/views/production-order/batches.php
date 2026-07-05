<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $batches */

$this->title = 'Production Batches';
?>
<h1><?= Html::encode($this->title) ?></h1>

<?php if (empty($batches)): ?>
    <div class="alert alert-success">No pending production tasks.</div>
<?php else: ?>
    <?php foreach ($batches as $batchDate => $tasks): ?>
        <h3><?= Html::encode($batchDate) ?></h3>
        <table class="table">
            <thead>
                <tr><th>Order</th><th>Status</th><th>Source</th><th>Assignee</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?= Html::a('Order #' . $task->order_id, ['order/view', 'id' => $task->order_id]) ?></td>
                    <td><?= Html::encode($task->status) ?></td>
                    <td>
                        <span class="badge <?= $task->source === 'produce' ? 'bg-warning' : 'bg-info' ?>">
                            <?= Html::encode($task->source) ?>
                        </span>
                    </td>
                    <td><?= $task->assignee ? Html::encode($task->assignee->username) : '-' ?></td>
                    <td><?= Html::a('View', ['view', 'id' => $task->id]) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
<?php endif; ?>
