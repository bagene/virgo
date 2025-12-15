<?php

declare(strict_types=1);

namespace App\Processes\Order;

use App\Contracts\OrderRuleInterface;
use App\DTO\Order\CreateOrderDTO;
use App\Models\Order;
use App\Models\User;
use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Facades\Auth;

class SellOrderProcess extends BaseOrderProcess
{
    /**
     * @param array<int, class-string<OrderRuleInterface>> $rules
     */
    public function __construct(
        #[Config('orders.rules.sell')]
        array $rules
    ) {
        parent::__construct($rules);
    }

    public function handle(CreateOrderDTO $data): Order
    {
        /** @var User $user */
        $user = Auth::user();
        $asset = $user->assets()
            ->where('symbol', $data->symbol)
            ->firstOrFail();

        $asset->amount -= $data->amount;
        $asset->locked_amount += $data->amount;
        $asset->save();

        // Call parent handle to create the order
        return parent::handle($data);
    }
}
