<?php

use App\Processes\Order\Rules\SufficientAsset;
use App\Processes\Order\Rules\SufficientBalance;

return [
    'rules' => [
        'buy' => [
            SufficientBalance::class,
        ],
        'sell' => [
            SufficientAsset::class,
        ],
    ],
    'commission_percentage' => 0.015,
];
