<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "production_order".
 *
 * @property int $id
 * @property int $order_id
 * @property int|null $user_id
 * @property string $status
 * @property string $source
 * @property string|null $batch_date
 * @property string|null $notes
 * @property int $created_at
 * @property int $updated_at
 */
class ProductionOrder extends ActiveRecord
{
    public const STATUS_PENDING = 'Pending';
    public const STATUS_IN_PROGRESS = 'In Progress';
    public const STATUS_DONE = 'Done';

    public const SOURCE_STOCK = 'stock';
    public const SOURCE_PRODUCE = 'produce';

    public static function tableName(): string
    {
        return 'production_order';
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
            [['order_id'], 'required'],
            [['order_id', 'user_id'], 'integer'],
            [['status'], 'string', 'max' => 30],
            [['status'], 'default', 'value' => self::STATUS_PENDING],
            [['status'], 'in', 'range' => self::statusList()],
            [['source'], 'string', 'max' => 20],
            [['source'], 'default', 'value' => self::SOURCE_PRODUCE],
            [['source'], 'in', 'range' => self::sourceList()],
            [['batch_date'], 'date', 'format' => 'php:Y-m-d'],
            [['notes'], 'string'],
            [['order_id'], 'exist', 'targetClass' => Order::class, 'targetAttribute' => 'id'],
            [['order_id'], 'unique'],
            [['user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
        ];
    }

    public static function statusList(): array
    {
        return [self::STATUS_PENDING, self::STATUS_IN_PROGRESS, self::STATUS_DONE];
    }

    public static function sourceList(): array
    {
        return [self::SOURCE_STOCK, self::SOURCE_PRODUCE];
    }

    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    public function getAssignee()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
