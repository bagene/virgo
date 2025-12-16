<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Enums\OrderStatusEnum;
use App\Enums\SymbolEnum;
use App\Models\Order;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

final readonly class ListOrdersAction
{
    public function __construct(
        #[CurrentUser]
        private User $user,
        private Request $request,
    ) {
        //
    }

    /**
     * @return Collection<int, Order>
     */
    public function handle(): Collection
    {
        return $this->user->orders()
            ->when($this->request->has('symbol'), function (Builder $query) {
                $query->where('symbol', SymbolEnum::tryFrom($this->request->query('symbol', '')));
            })
            ->when($this->request->has('status'), function (Builder $query) {
                $query->where('status', OrderStatusEnum::fromLabel($this->request->query('status', '')));
            })
            ->get();
    }
}
