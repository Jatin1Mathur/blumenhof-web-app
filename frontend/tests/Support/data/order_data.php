<?php

return [
    'confirmed_order_with_stock' => [
        'id' => 1,
        'customer_company_id' => 1,
        'user_id' => 101,
        'status' => 'Confirmed',
        'created_at' => time(),
        'updated_at' => time(),
    ],
    'confirmed_order_no_task_yet' => [
        'id' => 2,
        'customer_company_id' => 1,
        'user_id' => 101,
        'status' => 'Confirmed',
        'created_at' => time(),
        'updated_at' => time(),
    ],
    'draft_order' => [
        'id' => 3,
        'customer_company_id' => 1,
        'user_id' => 101,
        'status' => 'Draft',
        'created_at' => time(),
        'updated_at' => time(),
    ],
];
