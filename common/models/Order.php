<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int|null $customer_company_id
 * @property int|null $customer_contact_id
 * @property int $user_id
 * @property string $status
 * @property string|null $delivery_date
 * @property string|null $notes
 * @property int $created_at
 * @property int $updated_at
 */
class Order extends ActiveRecord
{
    public const STATUS_DRAFT = 'Draft';
    public const STATUS_CONFIRMED = 'Confirmed';
    public const STATUS_IN_PREPARATION = 'In Preparation';
    public const STATUS_READY = 'Ready';
    public const STATUS_DELIVERED = 'Delivered';
    public const STATUS_COMPLETED = 'Completed';

    public static function tableName(): string
    {
        return 'order';
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
            [['user_id'], 'required'],
            [['customer_company_id', 'customer_contact_id', 'user_id'], 'integer'],
            [['status'], 'string', 'max' => 30],
            [['status'], 'default', 'value' => self::STATUS_DRAFT],
            [['status'], 'in', 'range' => self::statusList()],
            [['delivery_date'], 'date', 'format' => 'php:Y-m-d'],
            [['notes'], 'string'],
            ['customer_company_id', 'validateCustomerLinked'],
        ];
    }

    public function validateCustomerLinked(): void
    {
        if (empty($this->customer_company_id) && empty($this->customer_contact_id)) {
            $this->addError('customer_company_id', 'Please select a customer company or contact.');
        }
    }

    public static function statusList(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_CONFIRMED,
            self::STATUS_IN_PREPARATION,
            self::STATUS_READY,
            self::STATUS_DELIVERED,
            self::STATUS_COMPLETED,
        ];
    }

    public function getItems()
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    public function getTotal(): float
    {
        $total = 0.0;
        foreach ($this->items as $item) {
            $total += (float) $item->unit_price * $item->quantity;
        }
        return $total;
    }
}
