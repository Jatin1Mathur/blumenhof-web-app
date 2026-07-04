<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CustomerContact[] $contacts */

$this->title = 'Customer Contacts';
?>
<h1><?= Html::encode($this->title) ?></h1>

<p><?= Html::a('New Contact', ['create'], ['class' => 'btn btn-primary']) ?></p>

<table class="table">
    <thead>
        <tr><th>First Name</th><th>Last Name</th><th>Company</th><th>Email</th><th>Phone</th><th>Actions</th></tr>
    </thead>
    <tbody>
    <?php foreach ($contacts as $contact): ?>
        <tr>
            <td><?= Html::encode($contact->first_name) ?></td>
            <td><?= Html::encode($contact->last_name) ?></td>
            <td><?= $contact->company ? Html::encode($contact->company->name) : '-' ?></td>
            <td><?= Html::encode($contact->email) ?></td>
            <td><?= Html::encode($contact->phone) ?></td>
            <td>
                <?= Html::a('View', ['view', 'id' => $contact->id]) ?> |
                <?= Html::a('Edit', ['update', 'id' => $contact->id]) ?> |
                <?= Html::a('Delete', ['delete', 'id' => $contact->id], [
                    'data' => ['confirm' => 'Delete this contact?', 'method' => 'post'],
                ]) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
