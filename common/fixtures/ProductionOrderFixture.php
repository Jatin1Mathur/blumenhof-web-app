<?php

declare(strict_types=1);

namespace common\fixtures;

use common\models\ProductionOrder;
use yii\test\ActiveFixture;

class ProductionOrderFixture extends ActiveFixture
{
    public $modelClass = ProductionOrder::class;
}
