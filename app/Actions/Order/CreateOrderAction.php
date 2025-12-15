<?php

namespace App\Actions\Order;

use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;
use App\Processes\Order\CreateOrderProcessFactory;

final readonly class CreateOrderAction
{
    public function __construct(
        private CreateOrderRequest $request,
        private CreateOrderProcessFactory $processFactory,
    ) {
        //
    }

    public function handle(): Order
    {
        $data = $this->request->dto();

        $process = $this->processFactory->create($data->side);

        if ($error = $process->getError($data)) {
            throw new \RuntimeException($error);
        }

        return $process->handle($data);
    }
}
