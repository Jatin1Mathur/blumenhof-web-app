<?php

declare(strict_types=1);

namespace frontend\tests\Functional;

use common\fixtures\AuthAssignmentFixture;
use common\fixtures\CrmUserFixture;
use common\fixtures\CustomerCategoryFixture;
use common\models\CustomerCategory;
use frontend\tests\Support\FunctionalTester;

final class CustomerCategoryCest
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
            'customerCategories' => [
                'class' => CustomerCategoryFixture::class,
                'dataFile' => codecept_data_dir() . 'customer_category_data.php',
            ],
        ];
    }

    public function guestIsDeniedAccess(FunctionalTester $I): void
    {
        $I->amOnRoute('customer-category/index');
        $I->dontSee('Customer Categories', 'h1');
    }

    public function noRoleUserIsDeniedAccess(FunctionalTester $I): void
    {
        $I->amLoggedInAs(103);
        $I->amOnRoute('customer-category/index');
        $I->dontSee('Customer Categories', 'h1');
    }

    public function salesEmployeeIsDeniedAccess(FunctionalTester $I): void
    {
        // Category management is manager/owner/admin only per the access
        // matrix — salesEmployee is deliberately excluded, unlike the
        // CustomerCompany and CustomerContact controllers.
        $I->amLoggedInAs(102);
        $I->amOnRoute('customer-category/index');
        $I->dontSee('Customer Categories', 'h1');
    }

    public function managerCanViewCategoryIndex(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('customer-category/index');
        $I->see('Customer Categories', 'h1');
        $I->see('Hotel');
    }

    public function managerCanCreateCategory(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('customer-category/create');
        $I->submitForm('form', [
            'CustomerCategory[name]' => 'Corporate',
            'CustomerCategory[description]' => 'Corporate clients',
        ]);
        $I->seeRecord(CustomerCategory::class, ['name' => 'Corporate']);
    }

    public function managerCannotCreateDuplicateCategoryName(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('customer-category/create');
        $I->submitForm('form', [
            'CustomerCategory[name]' => 'Hotel',
        ]);
        $I->see('has already been taken.', '.help-block');
    }

    public function managerCanUpdateCategory(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('customer-category/update', ['id' => 1]);
        $I->submitForm('form', [
            'CustomerCategory[description]' => 'Updated description',
        ]);
        $I->seeRecord(CustomerCategory::class, ['id' => 1, 'description' => 'Updated description']);
    }

    public function managerCanDeleteCategory(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('customer-category/delete', ['id' => 1]);
        $I->dontSeeRecord(CustomerCategory::class, ['id' => 1]);
    }
}
