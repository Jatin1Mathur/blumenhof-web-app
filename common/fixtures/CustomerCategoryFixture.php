<?php

declare(strict_types=1);

namespace common\fixtures;

use common\models\CustomerCategory;
use yii\test\ActiveFixture;

class CustomerCategoryFixture extends ActiveFixture
{
    public $modelClass = CustomerCategory::class;
}
