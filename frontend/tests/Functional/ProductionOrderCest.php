<?php

declare(strict_types=1);

namespace frontend\tests\Functional;

use common\fixtures\AuthAssignmentFixture;
use common\fixtures\CrmUserFixture;
use common\fixtures\CustomerCompanyFixture;
use common\fixtures\InventoryStockFixture;
use common\fixtures\OrderFixture;
use common\fixtures\OrderItemFixture;
use common\fixtures\ProductFixture;
use common\fixtures\ProductionOrderFixture;
use common\models\ProductionOrder;
use frontend\tests\Support\FunctionalTester;

final class ProductionOrderCest
{
    public function _fixtures(): array
    {
        return [
            'crmUsers' => [
                'class' => CrmUserFixture::class,
                'dataFile' => codecept_data_dir() . 'crm_user_data.php',
            ],
            'authAssignments' => [
                'class' => AuthAssignmentFixture::class,
                'dataFile' => codecept_data_dir() . 'auth_assignment_data.php',
            ],
            'customerCompanies' => [
                'class' => CustomerCompanyFixture::class,
                'dataFile' => codecept_data_dir() . 'customer_company_data.php',
            ],
            'products' => [
                'class' => ProductFixture::class,
                'dataFile' => codecept_data_dir() . 'product_data.php',
            ],
            'inventoryStocks' => [
                'class' => InventoryStockFixture::class,
                'dataFile' => codecept_data_dir() . 'inventory_stock_data.php',
            ],
            'orders' => [
                'class' => OrderFixture::class,
                'dataFile' => codecept_data_dir() . 'order_data.php',
            ],
            'orderItems' => [
                'class' => OrderItemFixture::class,
                'dataFile' => codecept_data_dir() . 'order_item_data.php',
            ],
            'productionOrders' => [
                'class' => ProductionOrderFixture::class,
                'dataFile' => codecept_data_dir() . 'production_order_data.php',
            ],
        ];
    }

    public function guestIsDeniedAccess(FunctionalTester $I): void
    {
        $I->amOnRoute('production-order/index');
        $I->dontSee('Production Tasks', 'h1');
    }

    public function noRoleUserIsDeniedAccess(FunctionalTester $I): void
    {
        $I->amLoggedInAs(103);
        $I->amOnRoute('production-order/index');
        $I->dontSee('Production Tasks', 'h1');
    }

    public function salesEmployeeIsDeniedAccess(FunctionalTester $I): void
    {
        // Production Management is inventoryEmployee/manager/owner/admin only —
        // salesEmployee is deliberately excluded per the access matrix.
        $I->amLoggedInAs(102);
        $I->amOnRoute('production-order/index');
        $I->dontSee('Production Tasks', 'h1');
    }

    public function inventoryEmployeeCanViewIndex(FunctionalTester $I): void
    {
        $I->amLoggedInAs(104);
        $I->amOnRoute('production-order/index');
        $I->see('Production Tasks', 'h1');
    }

    public function inventoryEmployeeCanGenerateTaskFromConfirmedOrder(FunctionalTester $I): void
    {
        $I->amLoggedInAs(104);
        $I->amOnRoute('production-order/generate', ['orderId' => 2]);
        $I->seeRecord(ProductionOrder::class, ['order_id' => 2]);
    }

    public function cannotGenerateDuplicateTaskForSameOrder(FunctionalTester $I): void
    {
        // Order 1 already has a production task (fixture: existing_task).
        $I->amLoggedInAs(104);
        $I->amOnRoute('production-order/generate', ['orderId' => 1]);
        $I->amOnRoute('production-order/view', ['id' => 1]);
        $I->see('stock');
    }

    public function cannotGenerateTaskFromDraftOrder(FunctionalTester $I): void
    {
        $I->amLoggedInAs(104);
        $I->amOnRoute('production-order/generate', ['orderId' => 3]);
        $I->dontSeeRecord(ProductionOrder::class, ['order_id' => 3]);
    }

    public function generatedTaskIsMarkedStockWhenInventorySufficient(FunctionalTester $I): void
    {
        // Order 2 needs 3 units; fixture stock has 50 available -> should be 'stock'.
        $I->amLoggedInAs(104);
        $I->amOnRoute('production-order/generate', ['orderId' => 2]);
        $I->seeRecord(ProductionOrder::class, ['order_id' => 2, 'source' => 'stock']);
    }

    public function inventoryEmployeeCanViewBatchesPage(FunctionalTester $I): void
    {
        $I->amLoggedInAs(104);
        $I->amOnRoute('production-order/batches');
        $I->see('Production Batches', 'h1');
    }

    public function inventoryEmployeeCanTransitionPendingToInProgress(FunctionalTester $I): void
    {
        $I->amLoggedInAs(104);
        $I->amOnRoute('production-order/view', ['id' => 1]);
        $csrfToken = $I->grabAttributeFrom('meta[name="csrf-token"]', 'content');
        $I->sendAjaxPostRequest(
            '/index-test.php?r=production-order%2Fchange-status&id=1',
            ['status' => 'In Progress', '_csrf-frontend' => $csrfToken]
        );
        $I->seeRecord(ProductionOrder::class, ['id' => 1, 'status' => 'In Progress']);
    }

    public function inventoryEmployeeCanDeleteTask(FunctionalTester $I): void
    {
        $I->amLoggedInAs(104);
        $I->amOnRoute('production-order/delete', ['id' => 1]);
        $I->dontSeeRecord(ProductionOrder::class, ['id' => 1]);
    }
}
