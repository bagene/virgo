<?php

declare(strict_types=1);

namespace App\Processes\Order;

use App\Contracts\OrderProcess;
use App\Contracts\OrderRuleInterface;
use App\DTO\Order\CreateOrderDTO;
use App\Enums\OrderStatusEnum;
use App\Jobs\MatchOrderJob;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

abstract class BaseOrderProcess implements OrderProcess
{
    /**
     * @param  array<int, class-string<OrderRuleInterface>>  $rules
     */
    public function __construct(
        protected array $rules,
    ) {
        //
    }

    public function handle(CreateOrderDTO $data): Order
    {
        /** @var User $user */
        $user = Auth::user();
        $order = new Order;

        $order->user_id = $user->id;
        $order->side = $data->side;
        $order->symbol = $data->symbol;
        $order->amount = $data->amount;
        $order->price = $data->price;
        $order->status = OrderStatusEnum::OPEN;

        $order->save();

        MatchOrderJob::dispatch($order);

        return $order;
    }

    public function getError(CreateOrderDTO $data): ?string
    {
        foreach ($this->rules as $ruleClass) {
            /** @var OrderRuleInterface $rule */
            $rule = App::make($ruleClass);

            if (! $rule->execute($data)) {
                return $rule->message();
            }
        }

        return null;
    }
}
