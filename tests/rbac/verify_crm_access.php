<?php
/**
 * Manual RBAC verification script for FLR-26 (Enforce CRM access rules).
 *
 * Usage: php tests/rbac/verify_crm_access.php
 *
 * Confirms via Yii's authManager::checkAccess() that:
 * - manager role has access to salesEmployee-level and manager-level permissions
 * - salesEmployee role has access only to salesEmployee-level permissions
 * - a user with no role assignment is denied every permission (proves the
 *   no-auto-role-on-signup decision from FLR-60 is properly enforced)
 */

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../../console/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/main.php',
    require __DIR__ . '/../../common/config/main-local.php',
    require __DIR__ . '/../../console/config/main.php',
    require __DIR__ . '/../../console/config/main-local.php'
);

new yii\console\Application($config);

$auth = Yii::$app->authManager;

$usersToCheck = [
    1 => 'abc (manager)',
    2 => 'rbacdemo (salesEmployee)',
    999 => 'nonexistent/no-role user',
];

$rolesToCheck = ['salesEmployee', 'manager', 'owner', 'admin'];

foreach ($usersToCheck as $userId => $label) {
    echo "=== $label (user_id=$userId) ===\n";
    foreach ($rolesToCheck as $role) {
        $result = $auth->checkAccess($userId, $role) ? 'ALLOWED' : 'denied';
        echo "  can('$role'): $result\n";
    }
    echo "\n";
}
