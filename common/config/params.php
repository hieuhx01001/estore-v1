<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'params' => [
        'maskMoneyOptions' => [
            'prefix' => 'đ ',
            'suffix' => ' đ',
            'affixesStay' => true,
            'thousands' => ',',
            'decimal' => '.',
            'precision' => false,
            'allowZero' => false,
            'allowNegative' => false,
        ]
    ]
];
