<?php

declare(strict_types=1);

namespace frontend\tests\Functional;

use common\fixtures\AuthAssignmentFixture;
use common\fixtures\CrmUserFixture;
use common\fixtures\ProductCategoryFixture;
use common\fixtures\ProductFixture;
use common\models\Product;
use frontend\tests\Support\FunctionalTester;

final class ProductCest
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
        ];
    }

    public function guestIsDeniedAccess(FunctionalTester $I): void
    {
        $I->amOnRoute('product/index');
        $I->dontSee('Products', 'h1');
    }

    public function noRoleUserIsDeniedAccess(FunctionalTester $I): void
    {
        $I->amLoggedInAs(103);
        $I->amOnRoute('product/index');
        $I->dontSee('Products', 'h1');
    }

    public function inventoryEmployeeCanViewProductIndex(FunctionalTester $I): void
    {
        $I->amLoggedInAs(104);
        $I->amOnRoute('product/index');
        $I->see('Products', 'h1');
        $I->see('Red Rose Bouquet');
    }

    public function salesEmployeeCanViewProductIndex(FunctionalTester $I): void
    {
        $I->amLoggedInAs(102);
        $I->amOnRoute('product/index');
        $I->see('Products', 'h1');
    }

    public function managerCanCreateProduct(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('product/create');
        $I->submitForm('form', [
            'Product[name]' => 'Sunflower Bunch',
            'Product[price]' => '19.99',
            'Product[product_category_id]' => '1',
        ]);
        $I->seeRecord(Product::class, ['name' => 'Sunflower Bunch']);
    }

    public function managerCannotCreateProductWithoutRequiredFields(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('product/create');
        $I->submitForm('form', []);
        $I->see('Name cannot be blank.', '.help-block');
        $I->see('Price cannot be blank.', '.help-block');
    }

    public function managerCanUpdateProduct(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('product/update', ['id' => 1]);
        $I->submitForm('form', [
            'Product[name]' => 'Updated Rose Bouquet',
        ]);
        $I->seeRecord(Product::class, ['id' => 1, 'name' => 'Updated Rose Bouquet']);
    }

    public function managerCanViewProductDetail(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('product/view', ['id' => 1]);
        $I->see('Red Rose Bouquet');
        $I->see('Flowers');
    }

    public function managerCanDeleteProduct(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('product/delete', ['id' => 1]);
        $I->dontSeeRecord(Product::class, ['id' => 1]);
    }
}
