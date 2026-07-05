<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_category`.
 */
class m260704_162751_create_product_category_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('product_category', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull()->unique(),
            'description' => $this->string(255)->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->batchInsert('product_category', ['name', 'description', 'created_at', 'updated_at'], [
            ['Flowers', 'Individual flowers and stems', time(), time()],
            ['Bouquets', 'Arranged flower bouquets', time(), time()],
            ['Add-ons', 'Vases, cards, and extras', time(), time()],
            ['Services', 'Delivery and other services', time(), time()],
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('product_category');
    }
}
