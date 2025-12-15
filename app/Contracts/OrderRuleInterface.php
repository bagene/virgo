<?php

namespace App\Contracts;

use App\DTO\Order\CreateOrderDTO;

interface OrderRuleInterface
{
    public function execute(CreateOrderDTO $data): bool;

    public function message(): string;
}
