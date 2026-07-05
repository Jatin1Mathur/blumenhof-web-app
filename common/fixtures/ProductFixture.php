<?php

declare(strict_types=1);

namespace common\fixtures;

use common\models\Product;
use yii\test\ActiveFixture;

class ProductFixture extends ActiveFixture
{
    public $modelClass = Product::class;
}
