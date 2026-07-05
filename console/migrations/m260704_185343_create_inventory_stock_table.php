<?php

use yii\db\Migration;

/**
 * Handles the creation of table `inventory_stock`.
 */
class m260704_185343_create_inventory_stock_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('inventory_stock', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull()->defaultValue(0),
            'low_stock_threshold' => $this->integer()->notNull()->defaultValue(5),
            'expiry_date' => $this->date()->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-inventory_stock-product_id', 'inventory_stock', 'product_id');

        $this->addForeignKey(
            'fk-inventory_stock-product_id',
            'inventory_stock',
            'product_id',
            'product',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-inventory_stock-product_id', 'inventory_stock');
        $this->dropIndex('idx-inventory_stock-product_id', 'inventory_stock');
        $this->dropTable('inventory_stock');
    }
}
