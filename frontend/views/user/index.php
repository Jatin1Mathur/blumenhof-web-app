<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var common\models\User[] $users */
/** @var array $userRoles */
/** @var string[] $allRoles */

use yii\helpers\Html;

$this->title = 'User Management';
?>
<h1>User Management</h1>

<p class="text-body-secondary">
    Assign a role to each user. Each user can hold exactly one role at a time.
</p>

<table class="table">
    <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Current Role</th>
            <th>Change Role</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= Html::encode($user->username) ?></td>
            <td><?= Html::encode($user->email) ?></td>
            <td>
                <?php if ($userRoles[$user->id] !== null): ?>
                    <span class="badge text-bg-success"><?= Html::encode($userRoles[$user->id]) ?></span>
                <?php else: ?>
                    <span class="badge text-bg-secondary">none</span>
                <?php endif; ?>
            </td>
            <td>
                <?= Html::beginForm(['user/assign-role', 'id' => $user->id], 'post', ['class' => 'd-flex gap-2']) ?>
                    <?= Html::dropDownList(
                        'role',
                        $userRoles[$user->id],
                        array_combine($allRoles, $allRoles),
                        ['class' => 'form-select form-select-sm', 'style' => 'width:auto;']
                    ) ?>
                    <?= Html::submitButton('Save', ['class' => 'btn btn-sm btn-success']) ?>
                <?= Html::endForm() ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
