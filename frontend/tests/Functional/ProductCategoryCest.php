<?php

declare(strict_types=1);

namespace frontend\tests\Functional;

use common\fixtures\AuthAssignmentFixture;
use common\fixtures\CrmUserFixture;
use common\fixtures\ProductCategoryFixture;
use common\models\ProductCategory;
use frontend\tests\Support\FunctionalTester;

final class ProductCategoryCest
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
        ];
    }

    public function guestIsDeniedAccess(FunctionalTester $I): void
    {
        $I->amOnRoute('product-category/index');
        $I->dontSee('Product Categories', 'h1');
    }

    public function noRoleUserIsDeniedAccess(FunctionalTester $I): void
    {
        $I->amLoggedInAs(103);
        $I->amOnRoute('product-category/index');
        $I->dontSee('Product Categories', 'h1');
    }

    public function inventoryEmployeeCanViewCategoryIndex(FunctionalTester $I): void
    {
        $I->amLoggedInAs(104);
        $I->amOnRoute('product-category/index');
        $I->see('Product Categories', 'h1');
        $I->see('Flowers');
    }

    public function salesEmployeeCanViewCategoryIndex(FunctionalTester $I): void
    {
        $I->amLoggedInAs(102);
        $I->amOnRoute('product-category/index');
        $I->see('Product Categories', 'h1');
    }

    public function managerCanCreateCategory(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('product-category/create');
        $I->submitForm('form', [
            'ProductCategory[name]' => 'Bouquets',
            'ProductCategory[description]' => 'Arranged flower bouquets',
        ]);
        $I->seeRecord(ProductCategory::class, ['name' => 'Bouquets']);
    }

    public function managerCanUpdateCategory(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('product-category/update', ['id' => 1]);
        $I->submitForm('form', [
            'ProductCategory[description]' => 'Updated description',
        ]);
        $I->seeRecord(ProductCategory::class, ['id' => 1, 'description' => 'Updated description']);
    }

    public function managerCanDeleteCategory(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('product-category/delete', ['id' => 1]);
        $I->dontSeeRecord(ProductCategory::class, ['id' => 1]);
    }
}
