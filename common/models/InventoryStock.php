<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "inventory_stock".
 *
 * @property int $id
 * @property int $product_id
 * @property int $quantity
 * @property int $low_stock_threshold
 * @property string|null $expiry_date
 * @property int $created_at
 * @property int $updated_at
 */
class InventoryStock extends ActiveRecord
{
    /**
     * Number of days before expiry to start warning.
     */
    public const EXPIRY_WARNING_DAYS = 3;

    public static function tableName(): string
    {
        return 'inventory_stock';
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
            [['product_id'], 'required'],
            [['product_id', 'quantity', 'low_stock_threshold'], 'integer'],
            [['quantity'], 'default', 'value' => 0],
            [['low_stock_threshold'], 'default', 'value' => 5],
            [['expiry_date'], 'date', 'format' => 'php:Y-m-d'],
            [['product_id'], 'exist', 'targetClass' => Product::class, 'targetAttribute' => 'id'],
            [['product_id'], 'unique'],
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->low_stock_threshold;
    }

    public function isExpiringSoon(): bool
    {
        if (empty($this->expiry_date)) {
            return false;
        }

        $daysUntilExpiry = (strtotime($this->expiry_date) - strtotime('today')) / 86400;

        return $daysUntilExpiry >= 0 && $daysUntilExpiry <= self::EXPIRY_WARNING_DAYS;
    }

    public function isExpired(): bool
    {
        if (empty($this->expiry_date)) {
            return false;
        }

        return strtotime($this->expiry_date) < strtotime('today');
    }

    public function needsWarning(): bool
    {
        return $this->isLowStock() || $this->isExpiringSoon() || $this->isExpired();
    }

    /**
     * Returns all inventory_stock records that need a warning
     * (low stock, expiring soon, or expired).
     *
     * @return static[]
     */
    public static function findNeedingWarning(): array
    {
        return array_values(array_filter(
            static::find()->all(),
            static fn (self $stock) => $stock->needsWarning()
        ));
    }
}
