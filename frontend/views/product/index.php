<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Product[] $products */

$this->title = 'Products';
?>
<h1><?= Html::encode($this->title) ?></h1>

<p><?= Html::a('New Product', ['create'], ['class' => 'btn btn-primary']) ?></p>

<table class="table">
    <thead>
        <tr><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Perishable</th><th>Actions</th></tr>
    </thead>
    <tbody>
    <?php foreach ($products as $product): ?>
        <tr class="<?= ($product->stock && $product->stock->isLowStock()) ? 'table-danger' : '' ?>">
            <td><?= Html::encode($product->name) ?></td>
            <td><?= $product->category ? Html::encode($product->category->name) : '-' ?></td>
            <td><?= Html::encode(number_format((float) $product->price, 2)) ?></td>
            <td><?= $product->stock ? Html::encode($product->stock->quantity) : '-' ?></td>
            <td><?= $product->is_perishable ? 'Yes' : 'No' ?></td>
            <td>
                <?= Html::a('View', ['view', 'id' => $product->id]) ?> |
                <?= Html::a('Edit', ['update', 'id' => $product->id]) ?> |
                <?= Html::a('Delete', ['delete', 'id' => $product->id], [
                    'data' => ['confirm' => 'Delete this product?', 'method' => 'post'],
                ]) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
