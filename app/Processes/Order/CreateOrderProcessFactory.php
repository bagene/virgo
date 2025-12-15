<?php

declare(strict_types=1);

namespace App\Processes\Order;

use App\Contracts\OrderProcess;
use App\Enums\OrderSideEnum;
use Illuminate\Support\Facades\App;

class CreateOrderProcessFactory
{
    public function create(OrderSideEnum $side): OrderProcess
    {
        return match ($side) {
            OrderSideEnum::BUY => App::make(BuyOrderProcess::class),
            OrderSideEnum::SELL => App::make(SellOrderProcess::class),
        };
    }
}
