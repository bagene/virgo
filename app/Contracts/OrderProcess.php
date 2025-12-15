<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\Order\CreateOrderDTO;
use App\Models\Order;

interface OrderProcess
{
    public function handle(CreateOrderDTO $data): Order;

    public function getError(CreateOrderDTO $data): ?string;
}
