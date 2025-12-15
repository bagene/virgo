<?php

declare(strict_types=1);

namespace App\Enums;

use App\Concerns\EnumTool;

enum SymbolEnum: string
{
    use EnumTool;

    case BTC = 'BTC';
    case ETH = 'ETH';
}
