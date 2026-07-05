<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ProductCategory[] $categories */

$this->title = 'Product Categories';
?>
<h1><?= Html::encode($this->title) ?></h1>

<p><?= Html::a('New Category', ['create'], ['class' => 'btn btn-primary']) ?></p>

<table class="table">
    <thead><tr><th>Name</th><th>Description</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($categories as $category): ?>
        <tr>
            <td><?= Html::encode($category->name) ?></td>
            <td><?= Html::encode($category->description) ?></td>
            <td>
                <?= Html::a('Edit', ['update', 'id' => $category->id]) ?> |
                <?= Html::a('Delete', ['delete', 'id' => $category->id], [
                    'data' => ['confirm' => 'Delete this category?', 'method' => 'post'],
                ]) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
