<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property int|null $product_category_id
 * @property string $name
 * @property string|null $description
 * @property string $price
 * @property bool $is_perishable
 * @property string|null $image_path
 * @property int $created_at
 * @property int $updated_at
 */
class Product extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'product';
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
            [['name', 'price'], 'required'],
            [['name'], 'string', 'max' => 150],
            [['description'], 'string'],
            [['price'], 'number', 'min' => 0],
            [['is_perishable'], 'boolean'],
            [['is_perishable'], 'default', 'value' => false],
            [['image_path'], 'string', 'max' => 255],
            [['product_category_id'], 'integer'],
            [['product_category_id'], 'exist', 'targetClass' => ProductCategory::class, 'targetAttribute' => 'id'],
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(ProductCategory::class, ['id' => 'product_category_id']);
    }

    public function getStock()
    {
        return $this->hasOne(InventoryStock::class, ['product_id' => 'id']);
    }
}
