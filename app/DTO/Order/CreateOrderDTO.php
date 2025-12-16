<?php

declare(strict_types=1);

namespace App\DTO\Order;

use App\Enums\OrderSideEnum;
use App\Enums\SymbolEnum;

final readonly class CreateOrderDTO
{
    public function __construct(
        public OrderSideEnum $side,
        public SymbolEnum $symbol,
        public float $amount,
        public float $price,
    ) {
        //
    }
}
