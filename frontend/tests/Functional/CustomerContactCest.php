<?php

declare(strict_types=1);

namespace frontend\tests\Functional;

use common\fixtures\AuthAssignmentFixture;
use common\fixtures\CrmUserFixture;
use common\fixtures\CustomerCategoryFixture;
use common\fixtures\CustomerCompanyFixture;
use common\fixtures\CustomerContactFixture;
use common\models\CustomerContact;
use frontend\tests\Support\FunctionalTester;

final class CustomerContactCest
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
            'customerContacts' => [
                'class' => CustomerContactFixture::class,
                'dataFile' => codecept_data_dir() . 'customer_contact_data.php',
            ],
        ];
    }

    public function guestIsDeniedAccess(FunctionalTester $I): void
    {
        $I->amOnRoute('customer-contact/index');
        $I->dontSee('Customer Contacts', 'h1');
    }

    public function noRoleUserIsDeniedAccess(FunctionalTester $I): void
    {
        $I->amLoggedInAs(103);
        $I->amOnRoute('customer-contact/index');
        $I->dontSee('Customer Contacts', 'h1');
    }

    public function managerCanViewContactIndex(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('customer-contact/index');
        $I->see('Customer Contacts', 'h1');
        $I->see('Anna');
        $I->see('Schmidt');
    }

    public function salesEmployeeCanViewContactIndex(FunctionalTester $I): void
    {
        $I->amLoggedInAs(102);
        $I->amOnRoute('customer-contact/index');
        $I->see('Customer Contacts', 'h1');
    }

    public function managerCanCreateContact(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('customer-contact/create');
        $I->submitForm('form', [
            'CustomerContact[first_name]' => 'Max',
            'CustomerContact[last_name]' => 'Weber',
        ]);
        $I->seeRecord(CustomerContact::class, ['first_name' => 'Max', 'last_name' => 'Weber']);
    }

    public function managerCanCreateStandaloneContactWithoutCompany(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('customer-contact/create');
        $I->submitForm('form', [
            'CustomerContact[first_name]' => 'Julia',
            'CustomerContact[last_name]' => 'Fischer',
        ]);
        $I->seeRecord(CustomerContact::class, [
            'first_name' => 'Julia',
            'last_name' => 'Fischer',
            'customer_company_id' => null,
        ]);
    }

    public function managerCanUpdateContact(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('customer-contact/update', ['id' => 1]);
        $I->submitForm('form', [
            'CustomerContact[last_name]' => 'Schmidt-Updated',
        ]);
        $I->seeRecord(CustomerContact::class, ['id' => 1, 'last_name' => 'Schmidt-Updated']);
    }

    public function managerCanDeleteContact(FunctionalTester $I): void
    {
        $I->amLoggedInAs(101);
        $I->amOnRoute('customer-contact/delete', ['id' => 1]);
        $I->dontSeeRecord(CustomerContact::class, ['id' => 1]);
    }
}
