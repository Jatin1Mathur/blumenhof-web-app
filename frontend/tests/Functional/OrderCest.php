<?php

declare(strict_types=1);

namespace frontend\tests\Functional;

use common\fixtures\AuthAssignmentFixture;
use common\fixtures\CrmUserFixture;
use common\fixtures\CustomerCompanyFixture;
use common\fixtures\OrderFixture;
use common\fixtures\OrderItemFixture;
use common\fixtures\ProductFixture;
use common\models\Order;
use frontend\tests\Support\FunctionalTester;

final class OrderCest
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
            'orders' => [
                'class' => OrderFixture::class,
                'dataFile' => codecept_data_dir() . 'order_data.php',
            ],
            'orderItems' => [
                'class' => OrderItemFixture::class,
                'dataFile' => codecept_data_dir() . 'order_item_data.php',
            ],
        ];
    }

    public function guestIsDeniedAccess(FunctionalTester $I): void
    {
        $I->amOnRoute('order/index');
        $I->dontSee('Orders', 'h1');
    }

    public function noRoleUserIsDeniedAccess(FunctionalTester $I): void
    {
        $I->amLoggedInAs(103);
        $I->amOnRoute('order/index');
        $I->dontSee('Orders', 'h1');
    }

    public function salesEmployeeCanViewOrderIndex(FunctionalTester $I): void
    {
        $I->amLoggedInAs(102);
        $I->amOnRoute('order/index');
        $I->see('Orders', 'h1');
        $I->see('Test Hotel GmbH');
    }

    public function financialEmployeeCanViewOrderIndex(FunctionalTester $I): void
    {
        $I->amLoggedInAs(105);
        $I->amOnRoute('order/index');
        $I->see('Orders', 'h1');
    }

    public function managerCanCreateOrderWithMultipleItems(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('order/create');
        $I->submitForm('form', [
            'Order[customer_company_id]' => '1',
            'Order[status]' => 'Draft',
            'OrderItem[0][product_id]' => '1',
            'OrderItem[0][quantity]' => '3',
            'OrderItem[0][unit_price]' => '29.99',
        ]);
        $I->seeRecord(Order::class, ['customer_company_id' => 1, 'status' => 'Draft']);
    }

    public function managerCannotCreateOrderWithoutCustomer(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('order/create');
        $I->submitForm('form', [
            'OrderItem[0][product_id]' => '1',
            'OrderItem[0][quantity]' => '1',
            'OrderItem[0][unit_price]' => '29.99',
        ]);
        $I->see('Please select a customer company or contact.', '.help-block');
    }

    public function managerCanViewOrderDetail(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('order/view', ['id' => 1]);
        $I->see('Test Hotel GmbH');
        $I->see('Red Rose Bouquet');
    }

    public function managerCanTransitionDraftOrderToConfirmed(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('order/view', ['id' => 1]);
        $csrfToken = $I->grabAttributeFrom('meta[name="csrf-token"]', 'content');
        $I->sendAjaxPostRequest(
            '/index-test.php?r=order%2Fchange-status&id=1',
            ['status' => 'Confirmed', '_csrf-frontend' => $csrfToken]
        );
        $I->seeRecord(\common\models\Order::class, ['id' => 1, 'status' => 'Confirmed']);
    }

    public function managerCannotSkipStatusFromDraftToDelivered(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('order/view', ['id' => 1]);
        $I->dontSee('Move to: Delivered');
    }

    public function managerCanDeleteOrder(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('order/delete', ['id' => 2]);
        $I->dontSeeRecord(Order::class, ['id' => 2]);
    }
}
