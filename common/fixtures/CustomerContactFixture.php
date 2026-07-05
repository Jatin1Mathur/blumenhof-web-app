<?php

declare(strict_types=1);

namespace common\fixtures;

use common\models\CustomerContact;
use yii\test\ActiveFixture;

class CustomerContactFixture extends ActiveFixture
{
    public $modelClass = CustomerContact::class;
}
