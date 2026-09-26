<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * RBAC setup for blumenHof.
 * Defines all roles and permissions per the agreed access matrix.
 *
 * Run with:  php yii rbac/init
 */
class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        // $auth->removeAll(); // clean slate so this command is safe to re-run

        // ---- 1. PERMISSIONS ----
        // One view + manage pair per module. "manage" implies "view".
        $perms = [];
        foreach (['Dashboard','Crm','Orders','Production','Catalog','Finance'] as $m) {
            $perms["view$m"] = $auth->createPermission("view$m");
            $perms["view$m"]->description = "View $m";
            $auth->add($perms["view$m"]);

            $perms["manage$m"] = $auth->createPermission("manage$m");
            $perms["manage$m"]->description = "Manage $m";
            $auth->add($perms["manage$m"]);

            $auth->addChild($perms["manage$m"], $perms["view$m"]);
        }

        // Standalone permissions
        $downloadReports = $auth->createPermission('downloadReports');
        $downloadReports->description = 'Download reports (P&L, sales)';
        $auth->add($downloadReports);

        $manageUsers = $auth->createPermission('manageUsers');
        $manageUsers->description = 'Manage system users';
        $auth->add($manageUsers);

        // ---- 2. ROLES ----

        // Sales employee: manage Orders, view Catalog & Production
        $sales = $auth->createRole('salesEmployee');
        $auth->add($sales);
        $auth->addChild($sales, $perms['manageOrders']);
        $auth->addChild($sales, $perms['viewCatalog']);
        $auth->addChild($sales, $perms['viewProduction']);

        // Financial employee: manage Finance, view Orders, download reports
        $finance = $auth->createRole('financialEmployee');
        $auth->add($finance);
        $auth->addChild($finance, $perms['manageFinance']);
        $auth->addChild($finance, $perms['viewOrders']);
        $auth->addChild($finance, $downloadReports);

        // Inventory employee: manage Catalog & Production
        $inventory = $auth->createRole('inventoryEmployee');
        $auth->add($inventory);
        $auth->addChild($inventory, $perms['manageCatalog']);
        $auth->addChild($inventory, $perms['manageProduction']);

        // Manager: all three employee roles + CRM + Dashboard + view Finance
        $manager = $auth->createRole('manager');
        $auth->add($manager);
        $auth->addChild($manager, $sales);
        $auth->addChild($manager, $finance);
        $auth->addChild($manager, $inventory);
        $auth->addChild($manager, $perms['manageCrm']);
        $auth->addChild($manager, $perms['manageDashboard']);
        $auth->addChild($manager, $perms['viewFinance']);
        // To let manager fully manage Finance, swap the line above for $perms['manageFinance']

        // Owner: view everything + dashboard + download reports (no editing)
        $owner = $auth->createRole('owner');
        $auth->add($owner);
        foreach (['Dashboard','Crm','Orders','Production','Catalog','Finance'] as $m) {
            $auth->addChild($owner, $perms["view$m"]);
        }
        $auth->addChild($owner, $downloadReports);

        // Admin: only user management
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $manageUsers);

        $this->stdout("RBAC roles & permissions created successfully.\n");
        return ExitCode::OK;
    }

    /**
     * Adds the 'guest' role only, without touching any existing roles or
     * permissions. Safe to re-run: does nothing if the role already exists.
     *
     * Run with:  php yii rbac/add-guest
     */
    public function actionAddGuest()
    {
        $auth = Yii::$app->authManager;

        if ($auth->getRole('guest') !== null) {
            $this->stdout("Guest role already exists, nothing to do.\n");
            return ExitCode::OK;
        }

        $guest = $auth->createRole('guest');
        $guest->description = 'Default role for new signups. No module access.';
        $auth->add($guest);

        $this->stdout("Guest role created successfully.\n");
        return ExitCode::OK;
    }

    /**
     * Creates or updates the protected blumenhofadmin account and (re)assigns
     * the admin role to it. Safe to re-run at any time.
     *
     * The password is read from the ADMIN_PASSWORD environment variable and is
     * never stored in the code. Changing the variable and restarting the app
     * changes the admin password. If the variable is not set, nothing happens.
     *
     * Run with:  ADMIN_PASSWORD='your-password' php yii rbac/seed-admin
     */
    public function actionSeedAdmin()
    {
        $auth = Yii::$app->authManager;
        $username = 'blumenhofadmin';
        $email = getenv('ADMIN_EMAIL') ?: 'hofsemesterwork@gmail.com';
        $password = getenv('ADMIN_PASSWORD');

        if (empty($password)) {
            $this->stdout("ADMIN_PASSWORD is not set, skipping admin seeding.\n");
            return ExitCode::OK;
        }

        $user = \common\models\User::findOne(['username' => $username]);
        $isNew = ($user === null);

        if ($isNew) {
            $user = new \common\models\User();
            $user->username = $username;
            $user->email = $email;
            $user->generateAuthKey();
            $user->status = \common\models\User::STATUS_ACTIVE;
        }

        // The password always follows ADMIN_PASSWORD.
        $user->setPassword($password);

        if (!$user->save()) {
            $this->stderr("Failed to save admin user: " . json_encode($user->getErrors()) . "\n");
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $this->stdout($isNew
            ? "Created {$username} (id={$user->id}).\n"
            : "{$username} exists (id={$user->id}), password updated from ADMIN_PASSWORD.\n");

        $adminRole = $auth->getRole('admin');
        if ($adminRole === null) {
            $this->stderr("The 'admin' role does not exist yet. Run 'php yii rbac/init' first.\n");
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $auth->revokeAll($user->id);
        $auth->assign($adminRole, $user->id);

        $this->stdout("Admin role assigned to {$username}.\n");
        return ExitCode::OK;
    }
}
