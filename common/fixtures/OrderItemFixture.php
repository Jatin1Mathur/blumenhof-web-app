<?php

declare(strict_types=1);

namespace common\fixtures;

use common\models\OrderItem;
use yii\test\ActiveFixture;

class OrderItemFixture extends ActiveFixture
{
    public $modelClass = OrderItem::class;
}
