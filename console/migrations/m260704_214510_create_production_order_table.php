<?php

use yii\db\Migration;

/**
 * Handles the creation of table `production_order`.
 */
class m260704_214510_create_production_order_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('production_order', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->null(),
            'status' => $this->string(30)->notNull()->defaultValue('Pending'),
            'source' => $this->string(20)->notNull()->defaultValue('produce'),
            'batch_date' => $this->date()->null(),
            'notes' => $this->text()->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-production_order-order_id', 'production_order', 'order_id');
        $this->createIndex('idx-production_order-user_id', 'production_order', 'user_id');

        $this->addForeignKey(
            'fk-production_order-order_id',
            'production_order',
            'order_id',
            'order',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-production_order-user_id',
            'production_order',
            'user_id',
            'user',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-production_order-user_id', 'production_order');
        $this->dropForeignKey('fk-production_order-order_id', 'production_order');
        $this->dropTable('production_order');
    }
}
