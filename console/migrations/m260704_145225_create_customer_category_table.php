<?php

use yii\db\Migration;

/**
 * Handles the creation of table `customer_category`.
 */
class m260704_145225_create_customer_category_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('customer_category', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull()->unique(),
            'description' => $this->string(255)->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->batchInsert('customer_category', ['name', 'description', 'created_at', 'updated_at'], [
            ['Hotel', 'Hotel and hospitality clients', time(), time()],
            ['Event Planner', 'Event planning companies', time(), time()],
            ['Corporate', 'Corporate/business clients', time(), time()],
            ['Individual', 'Private/individual customers', time(), time()],
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('customer_category');
    }
}
