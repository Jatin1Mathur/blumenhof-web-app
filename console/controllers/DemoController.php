<?php
namespace console\controllers;

use Yii;
use common\models\User;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Creates or updates an active demo account with the manager role, so the
 * live demo can be explored without email verification.
 *
 * The password is read from MANAGER_PASSWORD. If it is not set, nothing happens.
 *
 * Run with:  MANAGER_PASSWORD='your-password' php yii demo/seed-manager
 */
class DemoController extends Controller
{
    public function actionSeedManager()
    {
        $password = getenv('MANAGER_PASSWORD');
        if (empty($password)) {
            $this->stdout("MANAGER_PASSWORD is not set, skipping demo manager.\n");
            return ExitCode::OK;
        }

        $username = getenv('MANAGER_USERNAME') ?: 'demomanager';
        $email = getenv('MANAGER_EMAIL') ?: 'demomanager@blumenhof.example';

        $auth = Yii::$app->authManager;
        $role = $auth->getRole('manager');
        if ($role === null) {
            $this->stdout("The 'manager' role does not exist yet, skipping demo manager.\n");
            return ExitCode::OK;
        }

        $user = User::findOne(['username' => $username]);
        $isNew = ($user === null);

        if ($isNew) {
            $user = new User();
            $user->username = $username;
            $user->email = $email;
            $user->generateAuthKey();
        }

        $user->status = User::STATUS_ACTIVE;
        $user->setPassword($password);

        if (!$user->save()) {
            $this->stdout("Could not save demo manager: " . json_encode($user->getErrors()) . "\n");
            return ExitCode::OK;
        }

        $auth->revokeAll($user->id);
        $auth->assign($role, $user->id);

        $this->stdout(($isNew ? "Created" : "Updated") . " {$username} (id={$user->id}) with the manager role.\n");
        return ExitCode::OK;
    }
}
