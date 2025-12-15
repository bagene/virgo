<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Order\CreateOrderAction;
use App\Actions\Order\ListOrdersAction;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function index(ListOrdersAction $action): JsonResponse
    {
        return response()->json($action->handle());
    }

    public function store(CreateOrderAction $action): JsonResponse
    {
        try {
            $order = $action->handle();

            return response()->json($order, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
