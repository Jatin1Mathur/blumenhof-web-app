<?php

use yii\db\Migration;

/**
 * Handles the creation of table `customer_company`.
 */
class m260704_143000_create_customer_company_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('customer_company', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'address' => $this->string(255)->null(),
            'city' => $this->string(100)->null(),
            'postal_code' => $this->string(20)->null(),
            'country' => $this->string(100)->null(),
            'phone' => $this->string(30)->null(),
            'email' => $this->string(255)->null(),
            'notes' => $this->text()->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('customer_company');
    }
}
