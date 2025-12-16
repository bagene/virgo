<?php

namespace App\Jobs;

use App\Enums\OrderSideEnum;
use App\Enums\OrderStatusEnum;
use App\Events\OrderMatched;
use App\Models\Asset;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

final class MatchOrderJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly Order $order,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $lock = Cache::lock('sync_orders');
        if ($this->order->side->is(OrderSideEnum::BUY)) {
            $this->handleBuyOrder();

            return;
        }

        $this->handleSellOrder();
        $lock->release();
    }

    private function handleBuyOrder(): void
    {
        /** @var Collection<int, Order> $matched */
        $matched = Order::query()
            ->where('status', OrderStatusEnum::OPEN)
            ->where('side', OrderSideEnum::SELL)
            ->where('symbol', $this->order->symbol)
            ->where('price', '<=', $this->order->price)
            ->lockForUpdate()
            ->get();

        foreach ($matched as $sellOrder) {
            if ($sellOrder->amount >= $this->order->amount) {
                $this->updateSellOrderBalances($sellOrder, $this->order->amount);
                $this->updateAsset($this->order, $this->order->amount);
                $this->updateOrder($sellOrder);
                $this->broadcastOrder($sellOrder);

                return;
            }

            $this->order->amount -= $sellOrder->amount;

            $this->updateSellOrderBalances($sellOrder, $sellOrder->amount);
            $this->updateAsset($this->order, $sellOrder->amount);
            $sellOrder->amount = 0;
            $sellOrder->status = OrderStatusEnum::FILLED;
            $this->broadcastOrder($sellOrder);
            $sellOrder->save();
        }

        $this->order->save();
    }

    private function handleSellOrder(): void
    {
        /** @var Collection<int, Order> $matched */
        $matched = Order::query()
            ->where('status', OrderStatusEnum::OPEN)
            ->where('side', OrderSideEnum::BUY)
            ->where('symbol', $this->order->symbol)
            ->where('price', '>=', $this->order->price)
            ->lockForUpdate()
            ->get();

        foreach ($matched as $buyOrder) {
            if ($buyOrder->amount >= $this->order->amount) {
                $this->updateAsset($buyOrder, $this->order->amount);
                $this->updateSellOrderBalances($this->order, $this->order->amount);

                $this->updateOrder($buyOrder);
                $this->broadcastOrder($buyOrder);

                return;
            }

            $this->order->amount -= $buyOrder->amount;

            $this->updateAsset($buyOrder, $buyOrder->amount);
            $this->updateSellOrderBalances($this->order, $buyOrder->amount);
            $buyOrder->amount = 0;
            $buyOrder->status = OrderStatusEnum::FILLED;
            $buyOrder->save();
            $this->broadcastOrder($buyOrder);
        }
    }

    private function updateSellOrderBalances(Order $sellOrder, float $amount): void
    {
        $user = $sellOrder->user;
        $asset = $user->assets()
            ->where('symbol', $sellOrder->symbol)
            ->firstOrFail();

        $asset->locked_amount -= $amount;
        $asset->save();

        $balanceAdded = bcmul(
            (string) $amount,
            (string) $sellOrder->price,
            18
        );

        // Deduct commission
        $balanceAdded = bcdiv(
            $balanceAdded,
            (string) (Config::get('orders.commission_percentage', 0) + 1),
            18
        );

        $user->balance += floatval($balanceAdded);

        $user->save();
    }

    private function updateAsset(Order $buyOrder, float $amount): void
    {
        $user = $buyOrder->user;
        $asset = $user->assets()
            ->where('symbol', $buyOrder->symbol)
            ->first();

        if (! $asset) {
            $asset = new Asset;
            $asset->symbol = $buyOrder->symbol;
            $asset->amount = $amount;
            $asset->locked_amount = 0.0;
            $asset->user()->associate($user);
            $asset->save();

            return;
        }

        $asset->amount += $amount;
        $asset->save();
    }

    private function updateOrder(Order $order): void
    {
        $order->amount -= $this->order->amount;
        if ($order->amount === 0.00) {
            $order->status = OrderStatusEnum::FILLED;
        }
        $order->save();

        $this->order->amount = 0;
        $this->order->status = OrderStatusEnum::FILLED;
        $this->order->save();
    }

    private function broadcastOrder(Order $matched): void
    {
        OrderMatched::dispatch($matched->user_id);
        OrderMatched::dispatch($this->order->user_id);
    }
}
