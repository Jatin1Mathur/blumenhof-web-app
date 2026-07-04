<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "order_item".
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $quantity
 * @property string $unit_price
 * @property int $created_at
 * @property int $updated_at
 */
class OrderItem extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'order_item';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules(): array
    {
        return [
            [['order_id', 'product_id'], 'required'],
            [['order_id', 'product_id'], 'integer'],
            [['quantity'], 'integer', 'min' => 1],
            [['quantity'], 'default', 'value' => 1],
            [['unit_price'], 'number', 'min' => 0],
            [['product_id'], 'exist', 'targetClass' => Product::class, 'targetAttribute' => 'id'],
        ];
    }

    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    public function getSubtotal(): float
    {
        return (float) $this->unit_price * $this->quantity;
    }
}
