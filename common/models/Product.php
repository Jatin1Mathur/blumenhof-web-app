<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use Yii;
use yii\web\UploadedFile;

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
    /**
     * @var UploadedFile|null Used only for handling the upload; not persisted directly.
     */
    public $imageFile;

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
            [['imageFile'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 2, 'skipOnEmpty' => true],
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

    /**
     * Handles saving the uploaded image file to disk and updating image_path.
     * Call this after the model has been loaded and validated, before/after save().
     */
    public function uploadImage(): bool
    {
        if ($this->imageFile === null) {
            return true;
        }

        $uploadDir = Yii::getAlias('@frontend/web/uploads/products');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = uniqid('product_', true) . '.' . $this->imageFile->extension;
        $filePath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

        if ($this->imageFile->saveAs($filePath)) {
            $this->image_path = 'uploads/products/' . $fileName;
            $this->imageFile = null;
            return true;
        }

        return false;
    }
}
