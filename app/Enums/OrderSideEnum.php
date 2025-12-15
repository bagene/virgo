<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderSideEnum: int
{
    case BUY = 1;
    case SELL = 2;
}
