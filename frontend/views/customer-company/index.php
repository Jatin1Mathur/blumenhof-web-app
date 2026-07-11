<?php

declare(strict_types=1);

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CustomerCompany[] $companies */
/** @var common\models\CustomerContact[] $individuals */

$this->title = 'Customers';
?>
<div class="module-page-header">
    <div>
        <h1><?= Html::encode($this->title) ?></h1>
        <p class="text-body-secondary mb-0">Companies and individual customers, organized by category.</p>
    </div>
    <div class="d-flex gap-2">
        <?= Html::a('+ New Company', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('+ New Individual Customer', ['/customer-contact/create'], ['class' => 'btn btn-outline-success']) ?>
    </div>
</div>

<h2 class="h5 fw-bold mb-3">🏢 Companies</h2>
<div class="table-responsive module-table-wrap mb-4">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>City</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($companies as $company): ?>
            <tr>
                <td class="fw-semibold"><?= Html::encode($company->name) ?></td>
                <td>
                    <?php if ($company->category): ?>
                        <span class="badge text-bg-light module-status-badge module-category-badge"><?= Html::encode($company->category->name) ?></span>
                    <?php else: ?>
                        <span class="text-body-secondary">-</span>
                    <?php endif; ?>
                </td>
                <td><?= Html::encode($company->city) ?></td>
                <td><?= Html::encode($company->phone) ?></td>
                <td><?= Html::encode($company->email) ?></td>
                <td>
                    <?= Html::a('View', ['view', 'id' => $company->id], ['class' => 'btn btn-sm btn-outline-secondary']) ?>
                    <?= Html::a('Edit', ['update', 'id' => $company->id], ['class' => 'btn btn-sm btn-outline-secondary']) ?>
                    <?= Html::a('Delete', ['delete', 'id' => $company->id], [
                        'class' => 'btn btn-sm btn-outline-danger',
                        'data' => ['confirm' => 'Delete this company?', 'method' => 'post'],
                    ]) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($companies)): ?>
            <tr><td colspan="6" class="text-center text-body-secondary">No companies yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<h2 class="h5 fw-bold mb-3">🧍 Individual Customers</h2>
<div class="table-responsive module-table-wrap">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($individuals as $contact): ?>
            <tr>
                <td class="fw-semibold"><?= Html::encode($contact->first_name . ' ' . $contact->last_name) ?></td>
                <td><?= Html::encode($contact->email ?? '-') ?></td>
                <td><?= Html::encode($contact->phone ?? '-') ?></td>
                <td>
                    <?= Html::a('View', ['/customer-contact/view', 'id' => $contact->id], ['class' => 'btn btn-sm btn-outline-secondary']) ?>
                    <?= Html::a('Edit', ['/customer-contact/update', 'id' => $contact->id], ['class' => 'btn btn-sm btn-outline-secondary']) ?>
                    <?= Html::a('Delete', ['/customer-contact/delete', 'id' => $contact->id], [
                        'class' => 'btn btn-sm btn-outline-danger',
                        'data' => ['confirm' => 'Delete this contact?', 'method' => 'post'],
                    ]) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($individuals)): ?>
            <tr><td colspan="4" class="text-center text-body-secondary">No individual customers yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
