<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class UserController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['manageUsers'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all users along with their currently assigned role, if any.
     */
    public function actionIndex()
    {
        $users = User::find()->orderBy(['id' => SORT_ASC])->all();
        $authManager = Yii::$app->authManager;

        $userRoles = [];
        foreach ($users as $user) {
            $roles = $authManager->getRolesByUser($user->id);
            $userRoles[$user->id] = $roles !== [] ? array_key_first($roles) : null;
        }

        $allRoles = array_keys($authManager->getRoles());
        sort($allRoles);

        return $this->render('index', [
            'users' => $users,
            'userRoles' => $userRoles,
            'allRoles' => $allRoles,
        ]);
    }

    /**
     * Assigns (or reassigns) a single role to a user. A user can only ever
     * hold one role at a time in this system, so any existing role
     * assignment for that user is revoked first.
     */
    public function actionAssignRole(int $id)
    {
        $user = User::findOne($id);
        if ($user === null) {
            throw new NotFoundHttpException('The requested user does not exist.');
        }

        $roleName = Yii::$app->request->post('role');
        $authManager = Yii::$app->authManager;
        $role = $authManager->getRole($roleName);

        if ($role === null) {
            Yii::$app->session->setFlash('error', 'Unknown role selected.');
            return $this->redirect(['index']);
        }

        $authManager->revokeAll($user->id);
        $authManager->assign($role, $user->id);

        Yii::$app->session->setFlash('success', "Role for {$user->username} set to {$roleName}.");
        return $this->redirect(['index']);
    }
}
