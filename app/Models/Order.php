<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrderSideEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\SymbolEnum;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
 * @property-read string $side_label
 * @property OrderStatusEnum $status
 * @property-read string $status_label
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 *
 * Relationships:
 * @property-read User $user
 */
class Order extends Model
{
    /** @var list<string> $appends */
    protected $appends = ['side_label', 'status_label'];
    /**
     * @return string[]
     */
    public function casts(): array
    {
        return [
            'symbol' => SymbolEnum::class,
            'side' => OrderSideEnum::class,
            'status' => OrderStatusEnum::class,
        ];
    }

    /**
     * @param $attributes
     * @return array<string>
     */
    public function append($attributes): array
    {
        return array_merge($attributes, ['side_label', 'status_label']);
    }

    public function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status->label(),
        );
    }

    public function sideLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->side->label(),
        );
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
