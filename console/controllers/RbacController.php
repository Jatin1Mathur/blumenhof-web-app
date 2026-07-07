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
}