<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_item`.
 */
class m260704_193207_create_order_item_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('order_item', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull()->defaultValue(1),
            'unit_price' => $this->decimal(10, 2)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-order_item-order_id', 'order_item', 'order_id');
        $this->createIndex('idx-order_item-product_id', 'order_item', 'product_id');

        $this->addForeignKey(
            'fk-order_item-order_id',
            'order_item',
            'order_id',
            'order',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-order_item-product_id',
            'order_item',
            'product_id',
            'product',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-order_item-product_id', 'order_item');
        $this->dropForeignKey('fk-order_item-order_id', 'order_item');
        $this->dropTable('order_item');
    }
}
