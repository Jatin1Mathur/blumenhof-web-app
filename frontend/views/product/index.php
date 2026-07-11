<?php

declare(strict_types=1);

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Product[] $products */

$this->title = 'Products';
?>
<div class="module-page-header">
    <div>
        <h1><?= Html::encode($this->title) ?></h1>
        <p class="text-body-secondary mb-0">Manage products, categories, and stock levels.</p>
    </div>
    <?= Html::a('+ New Product', ['create'], ['class' => 'btn btn-success']) ?>
</div>

<div class="table-responsive module-table-wrap">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Perishable</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product): ?>
            <?php $isLow = $product->stock && $product->stock->isLowStock(); ?>
            <tr class="<?= $isLow ? 'module-row-low-stock' : '' ?>">
                <td>
                    <img src="<?= $product->image_path ? Html::encode(Yii::getAlias('@web/' . $product->image_path)) : Yii::getAlias('@web/images/product-placeholder.svg') ?>"
                         alt="<?= Html::encode($product->name) ?>"
                         style="width:48px;height:48px;object-fit:cover;border-radius:8px;">
                </td>
                <td class="fw-semibold"><?= Html::encode($product->name) ?></td>
                <td><?= $product->category ? Html::encode($product->category->name) : '-' ?></td>
                <td class="fw-semibold">&euro;<?= Html::encode(number_format((float) $product->price, 2)) ?></td>
                <td>
                    <?= $product->stock ? Html::encode($product->stock->quantity) : '-' ?>
                    <?php if ($isLow): ?>
                        <span class="badge text-bg-danger module-status-badge ms-1">Low</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($product->is_perishable): ?>
                        <span class="badge text-bg-warning module-status-badge">Yes</span>
                    <?php else: ?>
                        <span class="badge text-bg-secondary module-status-badge">No</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?= Html::a('View', ['view', 'id' => $product->id], ['class' => 'btn btn-sm btn-outline-secondary']) ?>
                    <?= Html::a('Edit', ['update', 'id' => $product->id], ['class' => 'btn btn-sm btn-outline-secondary']) ?>
                    <?= Html::a('Delete', ['delete', 'id' => $product->id], [
                        'class' => 'btn btn-sm btn-outline-danger',
                        'data' => ['confirm' => 'Delete this product?', 'method' => 'post'],
                    ]) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
