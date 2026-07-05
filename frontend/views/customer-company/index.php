<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CustomerCompany[] $companies */

$this->title = 'Customer Companies';
?>
<h1><?= Html::encode($this->title) ?></h1>

<p><?= Html::a('New Company', ['create'], ['class' => 'btn btn-primary']) ?></p>

<table class="table">
    <thead>
        <tr><th>Name</th><th>City</th><th>Phone</th><th>Email</th><th>Actions</th></tr>
    </thead>
    <tbody>
    <?php foreach ($companies as $company): ?>
        <tr>
            <td><?= Html::encode($company->name) ?></td>
            <td><?= Html::encode($company->city) ?></td>
            <td><?= Html::encode($company->phone) ?></td>
            <td><?= Html::encode($company->email) ?></td>
            <td>
                <?= Html::a('View', ['view', 'id' => $company->id]) ?> |
                <?= Html::a('Edit', ['update', 'id' => $company->id]) ?> |
                <?= Html::a('Delete', ['delete', 'id' => $company->id], [
                    'data' => ['confirm' => 'Delete this company?', 'method' => 'post'],
                ]) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
