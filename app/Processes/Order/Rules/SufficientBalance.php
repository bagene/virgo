<?php

declare(strict_types=1);

namespace App\Processes\Order\Rules;

use App\Contracts\OrderRuleInterface;
use App\DTO\Order\CreateOrderDTO;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;

final readonly class SufficientBalance implements OrderRuleInterface
{
    public function __construct(
        #[CurrentUser]
        private User $user,
    ) {
        //
    }

    public function execute(CreateOrderDTO $data): bool
    {
        $requiredBalance = bcmul(
            (string) $data->amount,
            (string) $data->price,
            18
        );

        return bccomp(
            (string) $this->user->balance,
            $requiredBalance,
            18
        ) >= 0;
    }

    public function message(): string
    {
        return 'Insufficient balance to place buy order.';
    }
}
