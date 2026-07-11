<?php

declare(strict_types=1);

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CustomerCategory[] $categories */

$this->title = 'Customer Categories';
?>
<div class="module-page-header">
    <div>
        <h1><?= Html::encode($this->title) ?></h1>
        <p class="text-body-secondary mb-0">Classify customer companies (Hotel, Corporate, and more).</p>
    </div>
    <?= Html::a('+ New Category', ['create'], ['class' => 'btn btn-success']) ?>
</div>

<div class="table-responsive module-table-wrap">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($categories as $category): ?>
            <tr>
                <td class="fw-semibold"><?= Html::encode($category->name) ?></td>
                <td class="text-body-secondary"><?= Html::encode($category->description) ?></td>
                <td>
                    <?= Html::a('Edit', ['update', 'id' => $category->id], ['class' => 'btn btn-sm btn-outline-secondary']) ?>
                    <?= Html::a('Delete', ['delete', 'id' => $category->id], [
                        'class' => 'btn btn-sm btn-outline-danger',
                        'data' => ['confirm' => 'Delete this category?', 'method' => 'post'],
                    ]) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
