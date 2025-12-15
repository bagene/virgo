<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrderSideEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\SymbolEnum;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Attributes:
 *
 * @property-read int $id
 * @property int $user_id
 * @property float $price
 * @property float $amount
 * @property SymbolEnum $symbol
 * @property OrderSideEnum $side
 * @property OrderStatusEnum $status
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 *
 * Relationships:
 * @property-read User $user
 *
 */
class Order extends Model
{
    /**
     * @return string[]
     */
    public function casts(): array
    {
        return  [
            'symbol' => SymbolEnum::class,
            'side' => OrderSideEnum::class,
            'status' => OrderStatusEnum::class,
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
