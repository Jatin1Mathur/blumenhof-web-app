<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product`.
 */
class m260704_185200_create_product_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('product', [
            'id' => $this->primaryKey(),
            'product_category_id' => $this->integer()->null(),
            'name' => $this->string(150)->notNull(),
            'description' => $this->text()->null(),
            'price' => $this->decimal(10, 2)->notNull(),
            'is_perishable' => $this->boolean()->notNull()->defaultValue(false),
            'image_path' => $this->string(255)->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-product-product_category_id', 'product', 'product_category_id');

        $this->addForeignKey(
            'fk-product-product_category_id',
            'product',
            'product_category_id',
            'product_category',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-product-product_category_id', 'product');
        $this->dropIndex('idx-product-product_category_id', 'product');
        $this->dropTable('product');
    }
}
