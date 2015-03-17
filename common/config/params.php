<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'params' => [
        'maskMoneyOptions' => [
            'prefix' => 'US$ ',
            'suffix' => ' c',
            'affixesStay' => true,
            'thousands' => ',',
            'decimal' => '.',
            'precision' => 2,
            'allowZero' => false,
            'allowNegative' => false,
        ]
    ]
];
