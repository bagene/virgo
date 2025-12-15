<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SymbolEnum;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Attributes:
 *
 * @property-read int $id
 * @property int $user_id
 * @property float $amount
 * @property float $locked_amount
 * @property SymbolEnum $symbol
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 *
 * Relationships:
 * @property-read User $user
 */
class Asset extends Model
{
    /**
     * @return string[]
     */
    public function casts(): array
    {
        return [
            'symbol' => SymbolEnum::class,
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
