<?php

declare(strict_types=1);

namespace frontend\tests\Functional;

use common\fixtures\AuthAssignmentFixture;
use common\fixtures\CrmUserFixture;
use common\fixtures\InventoryStockFixture;
use common\fixtures\ProductCategoryFixture;
use common\fixtures\ProductFixture;
use frontend\tests\Support\FunctionalTester;

final class StockWarningCest
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
            'productCategories' => [
                'class' => ProductCategoryFixture::class,
                'dataFile' => codecept_data_dir() . 'product_category_data.php',
            ],
            'products' => [
                'class' => ProductFixture::class,
                'dataFile' => codecept_data_dir() . 'product_data.php',
            ],
            'inventoryStocks' => [
                'class' => InventoryStockFixture::class,
                'dataFile' => codecept_data_dir() . 'inventory_stock_data.php',
            ],
        ];
    }

    public function guestIsDeniedAccess(FunctionalTester $I): void
    {
        $I->amOnRoute('stock-warning/index');
        $I->dontSee('Stock Warnings', 'h1');
    }

    public function noRoleUserIsDeniedAccess(FunctionalTester $I): void
    {
        $I->amLoggedInAs(103);
        $I->amOnRoute('stock-warning/index');
        $I->dontSee('Stock Warnings', 'h1');
    }

    public function inventoryEmployeeSeesNoWarningsWhenStockIsHealthy(FunctionalTester $I): void
    {
        // Fixture stock is quantity=10, threshold=5 — healthy, no warning expected.
        $I->amLoggedInAs(104);
        $I->amOnRoute('stock-warning/index');
        $I->see('Stock Warnings', 'h1');
        $I->see('No stock warnings');
    }

    public function inventoryEmployeeSeesWarningWhenStockIsLow(FunctionalTester $I): void
    {
        $I->amLoggedInAs(104);
        $I->amOnRoute('inventory-stock/update', ['id' => 1]);
        $I->submitForm('form', [
            'InventoryStock[quantity]' => '2',
            'InventoryStock[low_stock_threshold]' => '5',
        ]);
        $I->amOnRoute('stock-warning/index');
        $I->see('Red Rose Bouquet');
        $I->see('Low stock');
    }
}
