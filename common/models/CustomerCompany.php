<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "customer_company".
 *
 * @property int $id
 * @property string $name
 * @property int|null $customer_category_id
 * @property string|null $address
 * @property string|null $city
 * @property string|null $postal_code
 * @property string|null $country
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $notes
 * @property int $created_at
 * @property int $updated_at
 */
class CustomerCompany extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'customer_company';
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
            [['name', 'address', 'country', 'email'], 'string', 'max' => 255],
            [['city'], 'string', 'max' => 100],
            [['postal_code'], 'string', 'max' => 20],
            [['phone'], 'string', 'max' => 30],
            [['notes'], 'string'],
            [['email'], 'email'],
            [['customer_category_id'], 'integer'],
            [['customer_category_id'], 'exist', 'targetClass' => CustomerCategory::class, 'targetAttribute' => 'id'],
        ];
    }

    public function getContacts()
    {
        return $this->hasMany(CustomerContact::class, ['customer_company_id' => 'id']);
    }

    public function getCategory()
    {
        return $this->hasOne(CustomerCategory::class, ['id' => 'customer_category_id']);
    }
}
