<?php

return [
    'roles' => [
        'admin' => 1,
        'user' => 2,
        'super_admin' => 3,
    ],
    'statuses' => [
        'pending' => 1,
        'accepted' => 2,
        'processing' => 3,
        'dispatched' => 4,
        'success' => 5,
        'completed' => 6,
        'cancelled' => 7,
        'failed' => 8
    ],
    'file_types' => [
        'picture' => 1,
        'video' => 2,
        'document' => 3,
    ],
    'transaction_types' => [
        'wallet' => 1,
        'loan' => 2,
        'purchase' => 3,
        'repayment' => 4,
        'withdrawal' => 5,
    ],
    'interest_types' => [
        'fixed' => 1,
        'flexible' => 2,
        'none' => 3,
    ],
    'saving_types' => [
        'auto_debit' => 1,
        'monthly_debit' => 2,
        'manual' => 3,
    ],
    'loan_transaction_types' => [
        'direct' => 1,
        'pay_small' => 2,
        'wallet' => 3,
    ],
    'min_schedule_start' => env("MIN_SCHEDULE_START", 3),
    'schedule_start' => env("SCHEDULE_START", 30),
    'model_key' => "j9HafZdlfng79asd3T",
    'default_pagination' => env('RESOURCE_PAGINATION_DEFAULT', 50),
];
