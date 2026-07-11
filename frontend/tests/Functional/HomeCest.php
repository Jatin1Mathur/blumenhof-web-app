<?php

declare(strict_types=1);

namespace frontend\tests\Functional;

use frontend\tests\Support\FunctionalTester;

final class HomeCest
{
    public function checkOpen(FunctionalTester $I): void
    {
        $I->amOnRoute('site/index');
        $I->see('Everything your shop needs', 'h1');
        $I->seeLink('About');
    }
}
