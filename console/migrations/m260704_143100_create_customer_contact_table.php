<?php

use yii\db\Migration;

/**
 * Handles the creation of table `customer_contact`.
 */
class m260704_143100_create_customer_contact_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('customer_contact', [
            'id' => $this->primaryKey(),
            'customer_company_id' => $this->integer()->null(),
            'first_name' => $this->string(100)->notNull(),
            'last_name' => $this->string(100)->notNull(),
            'email' => $this->string(255)->null(),
            'phone' => $this->string(30)->null(),
            'role_title' => $this->string(100)->null(),
            'notes' => $this->text()->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-customer_contact-customer_company_id',
            'customer_contact',
            'customer_company_id'
        );

        $this->addForeignKey(
            'fk-customer_contact-customer_company_id',
            'customer_contact',
            'customer_company_id',
            'customer_company',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-customer_contact-customer_company_id', 'customer_contact');
        $this->dropIndex('idx-customer_contact-customer_company_id', 'customer_contact');
        $this->dropTable('customer_contact');
    }
}
