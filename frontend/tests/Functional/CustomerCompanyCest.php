<?php

declare(strict_types=1);

namespace frontend\tests\Functional;

use common\fixtures\AuthAssignmentFixture;
use common\fixtures\CrmUserFixture;
use common\fixtures\CustomerCategoryFixture;
use common\fixtures\CustomerCompanyFixture;
use common\models\CustomerCompany;
use frontend\tests\Support\FunctionalTester;

final class CustomerCompanyCest
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
            'customerCompanies' => [
                'class' => CustomerCompanyFixture::class,
                'dataFile' => codecept_data_dir() . 'customer_company_data.php',
            ],
        ];
    }

    public function guestIsDeniedAccess(FunctionalTester $I): void
    {
        $I->amOnRoute('customer-company/index');
        $I->dontSee('Customer Companies', 'h1');
    }

    public function noRoleUserIsDeniedAccess(FunctionalTester $I): void
    {
        $I->amLoggedInAs(103);
        $I->amOnRoute('customer-company/index');
        $I->dontSee('Customer Companies', 'h1');
    }

    public function managerCanViewCompanyIndex(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('customer-company/index');
        $I->see('Customer Companies', 'h1');
        $I->see('Test Hotel GmbH');
    }

    public function salesEmployeeCanViewCompanyIndex(FunctionalTester $I): void
    {
        $I->amLoggedInAs(102);
        $I->amOnRoute('customer-company/index');
        $I->see('Customer Companies', 'h1');
    }

    public function managerCanCreateCompany(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('customer-company/create');
        $I->submitForm('form', [
            'CustomerCompany[name]' => 'New Test Company',
            'CustomerCompany[city]' => 'Munich',
            'CustomerCompany[country]' => 'Germany',
        ]);
        $I->seeRecord(CustomerCompany::class, ['name' => 'New Test Company']);
    }

    public function managerCanUpdateCompany(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('customer-company/update', ['id' => 1]);
        $I->submitForm('form', [
            'CustomerCompany[name]' => 'Updated Hotel Name',
        ]);
        $I->seeRecord(CustomerCompany::class, ['id' => 1, 'name' => 'Updated Hotel Name']);
    }

    public function managerCanDeleteCompany(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('customer-company/delete', ['id' => 1]);
        $I->dontSeeRecord(CustomerCompany::class, ['id' => 1]);
    }
}
