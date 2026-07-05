<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "customer_contact".
 *
 * @property int $id
 * @property int|null $customer_company_id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $role_title
 * @property string|null $notes
 * @property int $created_at
 * @property int $updated_at
 */
class CustomerContact extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'customer_contact';
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
            [['first_name', 'last_name'], 'required'],
            [['first_name', 'last_name', 'role_title'], 'string', 'max' => 100],
            [['email'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['phone'], 'string', 'max' => 30],
            [['notes'], 'string'],
            [['customer_company_id'], 'integer'],
            [['customer_company_id'], 'exist', 'targetClass' => CustomerCompany::class, 'targetAttribute' => 'id'],
        ];
    }

    public function getCompany()
    {
        return $this->hasOne(CustomerCompany::class, ['id' => 'customer_company_id']);
    }
}
