<?php

use yii\db\Migration;

/**
 * Handles adding customer_category_id to table `customer_company`.
 */
class m260704_145423_add_customer_category_id_to_customer_company_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn(
            'customer_company',
            'customer_category_id',
            $this->integer()->null()->after('name')
        );

        $this->createIndex(
            'idx-customer_company-customer_category_id',
            'customer_company',
            'customer_category_id'
        );

        $this->addForeignKey(
            'fk-customer_company-customer_category_id',
            'customer_company',
            'customer_category_id',
            'customer_category',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-customer_company-customer_category_id', 'customer_company');
        $this->dropIndex('idx-customer_company-customer_category_id', 'customer_company');
        $this->dropColumn('customer_company', 'customer_category_id');
    }
}
