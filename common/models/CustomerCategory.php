<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "customer_category".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $created_at
 * @property int $updated_at
 */
class CustomerCategory extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'customer_category';
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
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['name'], 'unique'],
            [['description'], 'string', 'max' => 255],
        ];
    }

    public function getCompanies()
    {
        return $this->hasMany(CustomerCompany::class, ['customer_category_id' => 'id']);
    }
}
