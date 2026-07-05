<?php

declare(strict_types=1);

namespace common\fixtures;

use common\models\ProductCategory;
use yii\test\ActiveFixture;

class ProductCategoryFixture extends ActiveFixture
{
    public $modelClass = ProductCategory::class;
}
