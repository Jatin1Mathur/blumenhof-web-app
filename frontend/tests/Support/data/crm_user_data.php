<?php

return [
    'manager_user' => [
        'id' => 101,
        'username' => 'crm_manager',
        'auth_key' => 'crmManagerAuthKey123456789012345',
        'password_hash' => '$2y$13$nJ1WDlBaGcbCdbNC5.5l4.sgy.OMEKCqtDQOdQ2OWpgiKRWYyzzne',
        'email' => 'crm_manager@example.com',
        'status' => 10,
        'created_at' => time(),
        'updated_at' => time(),
    ],
    'sales_user' => [
        'id' => 102,
        'username' => 'crm_sales',
        'auth_key' => 'crmSalesAuthKey1234567890123456A',
        'password_hash' => '$2y$13$nJ1WDlBaGcbCdbNC5.5l4.sgy.OMEKCqtDQOdQ2OWpgiKRWYyzzne',
        'email' => 'crm_sales@example.com',
        'status' => 10,
        'created_at' => time(),
        'updated_at' => time(),
    ],
    'no_role_user' => [
        'id' => 103,
        'username' => 'crm_norole',
        'auth_key' => 'crmNoRoleAuthKey1234567890123456',
        'password_hash' => '$2y$13$nJ1WDlBaGcbCdbNC5.5l4.sgy.OMEKCqtDQOdQ2OWpgiKRWYyzzne',
        'email' => 'crm_norole@example.com',
        'status' => 10,
        'created_at' => time(),
        'updated_at' => time(),
    ],
    'inventory_user' => [
        'id' => 104,
        'username' => 'crm_inventory',
        'auth_key' => 'crmInventoryAuthKey123456789012A',
        'password_hash' => '$2y$13$nJ1WDlBaGcbCdbNC5.5l4.sgy.OMEKCqtDQOdQ2OWpgiKRWYyzzne',
        'email' => 'crm_inventory@example.com',
        'status' => 10,
        'created_at' => time(),
        'updated_at' => time(),
    ],
];
