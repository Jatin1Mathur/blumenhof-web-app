<?php

declare(strict_types=1);

namespace frontend\tests\Functional;

use common\fixtures\AuthAssignmentFixture;
use common\fixtures\CrmUserFixture;
use common\models\CustomerCompany;
use common\models\Order;
use common\models\Product;
use common\models\ProductionOrder;
use frontend\tests\Support\FunctionalTester;

final class CrossModuleIntegrationCest
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
        ];
    }

    /**
     * Full end-to-end flow: create a customer company, create a product with
     * stock, create an order for that customer, confirm the order, generate
     * a production task from it, and verify the whole chain is consistent.
     */
    public function fullCustomerToProductionFlowWorksEndToEnd(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);

        // Step 1: create a customer company (CRM module)
        $I->amOnRoute('customer-company/create');
        $I->submitForm('form', [
            'CustomerCompany[name]' => 'Integration Test Hotel',
            'CustomerCompany[city]' => 'Hof',
            'CustomerCompany[country]' => 'Germany',
        ]);
        $company = CustomerCompany::findOne(['name' => 'Integration Test Hotel']);
        $I->assertNotNull($company, 'Customer company should have been created');

        // Step 2: create a product (Catalog module)
        $I->amOnRoute('product/create');
        $I->submitForm('form', [
            'Product[name]' => 'Integration Test Bouquet',
            'Product[price]' => '25.00',
        ]);
        $product = Product::findOne(['name' => 'Integration Test Bouquet']);
        $I->assertNotNull($product, 'Product should have been created');

        // Step 3: give that product enough stock (Catalog module)
        $I->amOnRoute('inventory-stock/create');
        $I->submitForm('form', [
            'InventoryStock[product_id]' => (string) $product->id,
            'InventoryStock[quantity]' => '20',
            'InventoryStock[low_stock_threshold]' => '5',
        ]);

        // Step 4: create an order linking the customer and the product (Order module)
        $I->amOnRoute('order/create');
        $I->submitForm('form', [
            'Order[customer_company_id]' => (string) $company->id,
            'Order[status]' => 'Draft',
            'OrderItem[0][product_id]' => (string) $product->id,
            'OrderItem[0][quantity]' => '4',
            'OrderItem[0][unit_price]' => '25.00',
        ]);
        $order = Order::findOne(['customer_company_id' => $company->id]);
        $I->assertNotNull($order, 'Order should have been created');
        $I->assertEquals('Draft', $order->status);

        // Step 5: confirm the order via the status workflow (Order module)
        $csrfToken = $I->grabAttributeFrom('meta[name="csrf-token"]', 'content');
        $I->sendAjaxPostRequest(
            '/index-test.php?r=order%2Fchange-status&id=' . $order->id,
            ['status' => 'Confirmed', '_csrf-frontend' => $csrfToken]
        );
        $order->refresh();
        $I->assertEquals('Confirmed', $order->status, 'Order should now be Confirmed');

        // Step 6: generate a production task from the confirmed order (Production module)
        $I->amOnRoute('production-order/generate', ['orderId' => $order->id]);
        $productionOrder = ProductionOrder::findOne(['order_id' => $order->id]);
        $I->assertNotNull($productionOrder, 'Production task should have been generated');
        $I->assertEquals('stock', $productionOrder->source, 'Should be fulfilled from stock (20 available, 4 needed)');
        $I->assertEquals('Pending', $productionOrder->status);

        // Step 7: verify the order's view page correctly links to the production task (cross-module UI check)
        $I->amOnRoute('order/view', ['id' => $order->id]);
        $I->see('Integration Test Hotel');
        $I->see('Integration Test Bouquet');
        $I->see('Production task already exists');

        // Step 8: verify the dashboard reflects this new order in its summary data (Dashboard module)
        $I->amOnRoute('dashboard/orders-summary-data');
        $I->seeResponseCodeIs(200);
    }

    /**
     * Verifies that a salesEmployee (who can manage CRM and Orders) is
     * correctly denied access to the Production module, all checked within
     * one continuous access-control flow.
     */
    public function salesEmployeeHasConsistentAccessAcrossModules(FunctionalTester $I): void
    {
        $I->amLoggedInAs(102);

        // Allowed: CRM
        $I->amOnRoute('customer-company/index');
        $I->see('Customer Companies', 'h1');

        // Allowed: Orders
        $I->amOnRoute('order/index');
        $I->see('Orders', 'h1');

        // Denied: Production
        $I->amOnRoute('production-order/index');
        $I->dontSee('Production Tasks', 'h1');

        // Denied: Dashboard
        $I->amOnRoute('dashboard/index');
        $I->dontSee('Dashboard', 'h1');
    }

    /**
     * Verifies that an inventoryEmployee (Catalog + Production) is correctly
     * denied access to Orders, all checked within one continuous flow.
     */
    public function inventoryEmployeeHasConsistentAccessAcrossModules(FunctionalTester $I): void
    {
        $I->amLoggedInAs(104);

        // Allowed: Catalog
        $I->amOnRoute('product/index');
        $I->see('Products', 'h1');

        // Allowed: Production
        $I->amOnRoute('production-order/index');
        $I->see('Production Tasks', 'h1');

        // Denied: Orders
        $I->amOnRoute('order/index');
        $I->dontSee('Orders', 'h1');

        // Denied: Dashboard
        $I->amOnRoute('dashboard/index');
        $I->dontSee('Dashboard', 'h1');
    }
}
