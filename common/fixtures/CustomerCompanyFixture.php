<?php

declare(strict_types=1);

namespace common\fixtures;

use common\models\CustomerCompany;
use yii\test\ActiveFixture;

class CustomerCompanyFixture extends ActiveFixture
{
    public $modelClass = CustomerCompany::class;
}
