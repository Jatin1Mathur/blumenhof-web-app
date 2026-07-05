<?php

declare(strict_types=1);

namespace frontend\tests\Functional;

use common\fixtures\AuthAssignmentFixture;
use common\fixtures\CrmUserFixture;
use common\fixtures\InventoryStockFixture;
use common\fixtures\ProductCategoryFixture;
use common\fixtures\ProductFixture;
use common\models\InventoryStock;
use frontend\tests\Support\FunctionalTester;

final class InventoryStockCest
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
        $I->amOnRoute('inventory-stock/index');
        $I->dontSee('Inventory Stock', 'h1');
    }

    public function noRoleUserIsDeniedAccess(FunctionalTester $I): void
    {
        $I->amLoggedInAs(103);
        $I->amOnRoute('inventory-stock/index');
        $I->dontSee('Inventory Stock', 'h1');
    }

    public function salesEmployeeIsDeniedAccess(FunctionalTester $I): void
    {
        $I->amLoggedInAs(102);
        $I->amOnRoute('inventory-stock/index');
        $I->dontSee('Inventory Stock', 'h1');
    }

    public function inventoryEmployeeCanViewStockIndex(FunctionalTester $I): void
    {
        $I->amLoggedInAs(104);
        $I->amOnRoute('inventory-stock/index');
        $I->see('Inventory Stock', 'h1');
        $I->see('Red Rose Bouquet');
    }

    public function inventoryEmployeeCanCreateStock(FunctionalTester $I): void
    {
        $I->amLoggedInAs(104);
        $I->amOnRoute('inventory-stock/create');
        $I->submitForm('form', [
            'InventoryStock[quantity]' => '3',
            'InventoryStock[low_stock_threshold]' => '5',
        ]);
    }

    public function inventoryEmployeeCanUpdateStock(FunctionalTester $I): void
    {
        $I->amLoggedInAs(104);
        $I->amOnRoute('inventory-stock/update', ['id' => 1]);
        $I->submitForm('form', [
            'InventoryStock[quantity]' => '2',
        ]);
        $I->seeRecord(InventoryStock::class, ['id' => 1, 'quantity' => 2]);
    }

    public function stockBelowThresholdIsFlaggedAsLowStock(FunctionalTester $I): void
    {
        $I->amLoggedInAs(104);
        $I->amOnRoute('inventory-stock/update', ['id' => 1]);
        $I->submitForm('form', [
            'InventoryStock[quantity]' => '1',
            'InventoryStock[low_stock_threshold]' => '5',
        ]);
        $I->amOnRoute('inventory-stock/index');
        $I->see('Low Stock');
    }

    public function inventoryEmployeeCanDeleteStock(FunctionalTester $I): void
    {
        $I->amLoggedInAs(104);
        $I->amOnRoute('inventory-stock/delete', ['id' => 1]);
        $I->dontSeeRecord(InventoryStock::class, ['id' => 1]);
    }
}
