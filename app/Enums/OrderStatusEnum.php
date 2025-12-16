<?php

declare(strict_types=1);

namespace App\Enums;

use App\Concerns\EnumTool;

enum OrderStatusEnum: int
{
    use EnumTool;

    case OPEN = 1;
    case FILLED = 2;
    case CANCELED = 3;
}
