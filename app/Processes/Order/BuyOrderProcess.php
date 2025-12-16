<?php

declare(strict_types=1);

namespace App\Processes\Order;

use App\Contracts\OrderRuleInterface;
use App\DTO\Order\CreateOrderDTO;
use App\Models\Order;
use App\Models\User;
use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Facades\Auth;

final class BuyOrderProcess extends BaseOrderProcess
{
    /**
     * @param  array<int, class-string<OrderRuleInterface>>  $rules
     */
    public function __construct(
        #[Config('orders.rules.buy')]
        array $rules
    ) {
        parent::__construct($rules);
    }

    public function handle(CreateOrderDTO $data): Order
    {
        /** @var User $user */
        $user = Auth::user();
        $user->balance = floatval(bcsub(
            (string) $user->balance,
            bcmul(
                (string) $data->amount,
                (string) $data->price,
                18
            ),
            18
        ));
        $user->save();

        // Call parent handle to create the order
        return parent::handle($data);
    }
}
