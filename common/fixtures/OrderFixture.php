<?php

declare(strict_types=1);

namespace common\fixtures;

use common\models\Order;
use yii\test\ActiveFixture;

class OrderFixture extends ActiveFixture
{
    public $modelClass = Order::class;
}
