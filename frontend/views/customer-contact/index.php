<?php

declare(strict_types=1);

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CustomerContact[] $contacts */

$this->title = 'Customer Contacts';
?>
<div class="module-page-header">
    <div>
        <h1><?= Html::encode($this->title) ?></h1>
        <p class="text-body-secondary mb-0">Manage individual contacts, linked to a company or standalone.</p>
    </div>
    <?= Html::a('+ New Contact', ['create'], ['class' => 'btn btn-success']) ?>
</div>

<div class="table-responsive module-table-wrap">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Company</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($contacts as $contact): ?>
            <tr>
                <td class="fw-semibold"><?= Html::encode($contact->first_name) ?></td>
                <td class="fw-semibold"><?= Html::encode($contact->last_name) ?></td>
                <td><?= $contact->company ? Html::encode($contact->company->name) : '<span class="text-body-secondary">Standalone</span>' ?></td>
                <td><?= Html::encode($contact->email) ?></td>
                <td><?= Html::encode($contact->phone) ?></td>
                <td>
                    <?= Html::a('View', ['view', 'id' => $contact->id], ['class' => 'btn btn-sm btn-outline-secondary']) ?>
                    <?= Html::a('Edit', ['update', 'id' => $contact->id], ['class' => 'btn btn-sm btn-outline-secondary']) ?>
                    <?= Html::a('Delete', ['delete', 'id' => $contact->id], [
                        'class' => 'btn btn-sm btn-outline-danger',
                        'data' => ['confirm' => 'Delete this contact?', 'method' => 'post'],
                    ]) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
