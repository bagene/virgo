<?php

namespace App\Processes\Order\Rules;

use App\Contracts\OrderRuleInterface;
use App\DTO\Order\CreateOrderDTO;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;

final readonly class SufficientAsset implements OrderRuleInterface
{
    public function __construct(
        #[CurrentUser]
        private User $user,
    ) {
        //
    }

    public function execute(CreateOrderDTO $data): bool
    {
        return $this->user->assets()
            ->where('symbol', $data->symbol)
            ->first()?->amount >= $data->amount;
    }

    public function message(): string
    {
        return 'Insufficient asset to place sell order.';
    }
}
