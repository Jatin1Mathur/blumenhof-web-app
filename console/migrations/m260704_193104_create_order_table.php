<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m260704_193104_create_order_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'customer_company_id' => $this->integer()->null(),
            'customer_contact_id' => $this->integer()->null(),
            'user_id' => $this->integer()->notNull(),
            'status' => $this->string(30)->notNull()->defaultValue('Draft'),
            'delivery_date' => $this->date()->null(),
            'notes' => $this->text()->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-order-customer_company_id', 'order', 'customer_company_id');
        $this->createIndex('idx-order-customer_contact_id', 'order', 'customer_contact_id');
        $this->createIndex('idx-order-user_id', 'order', 'user_id');

        $this->addForeignKey(
            'fk-order-customer_company_id',
            'order',
            'customer_company_id',
            'customer_company',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-order-customer_contact_id',
            'order',
            'customer_contact_id',
            'customer_contact',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-order-user_id',
            'order',
            'user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-order-user_id', 'order');
        $this->dropForeignKey('fk-order-customer_contact_id', 'order');
        $this->dropForeignKey('fk-order-customer_company_id', 'order');
        $this->dropTable('order');
    }
}
