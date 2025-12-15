<?php

declare(strict_types=1);

namespace App\Enums;

use App\Concerns\EnumTool;

enum OrderSideEnum: int
{
    use EnumTool;

    case BUY = 1;
    case SELL = 2;
}
