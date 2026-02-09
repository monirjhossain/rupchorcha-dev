<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'nullable|string',
            'payment_status' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'courier_id' => 'nullable|exists:couriers,id',
            'tracking_number' => 'nullable|string',
            'admin_note' => 'nullable|string',
            'coupon_code' => 'nullable|string',
            'product_id' => 'array',
            'product_id.*' => 'integer|exists:products,id',
            'quantity' => 'array',
            'quantity.*' => 'integer|min:1',
            'unit_price' => 'array',
            'unit_price.*' => 'numeric|min:0',
        ];
    }
}
