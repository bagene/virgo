<?php

declare(strict_types=1);

namespace App\Processes\Order\Rules;

use App\Contracts\OrderRuleInterface;
use App\DTO\Order\CreateOrderDTO;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;

final readonly class AssetExist implements OrderRuleInterface
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
            ->exists();
    }

    public function message(): string
    {
        return 'The specified asset does not exist in your portfolio.';
    }
}
