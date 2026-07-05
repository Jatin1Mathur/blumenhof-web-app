<?php

declare(strict_types=1);

namespace common\fixtures;

use common\models\InventoryStock;
use yii\test\ActiveFixture;

class InventoryStockFixture extends ActiveFixture
{
    public $modelClass = InventoryStock::class;
}
