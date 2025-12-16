<?php

namespace App\Http\Requests;

use App\DTO\Order\CreateOrderDTO;
use App\Enums\OrderSideEnum;
use App\Enums\SymbolEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'side' => ['required', 'string', Rule::in(OrderSideEnum::labels())],
            'symbol' => ['required', 'string', Rule::enum(SymbolEnum::class)],
            'amount' => ['required', 'numeric', 'min:0.0001'],
            'price' => ['required', 'numeric', 'min:0.01'],
        ];
    }

    public function dto(): CreateOrderDTO
    {
        /**
         * @var array{side: string, symbol: string, amount: float, price: float} $validated
         */
        $validated = $this->validated();

        return new CreateOrderDTO(
            side: OrderSideEnum::fromLabel($validated['side']),
            symbol: SymbolEnum::from($validated['symbol']),
            amount: $validated['amount'],
            price: $validated['price'],
        );
    }
}
