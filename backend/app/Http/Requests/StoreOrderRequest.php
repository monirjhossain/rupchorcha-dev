<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'user_id' => 'nullable|exists:users,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'city' => 'required|string',
            'area' => 'required|string',
            'notes' => 'nullable|string',
            'payment_method' => 'required|string',
            'shipping_method' => 'required|string',
            'shipping_cost' => 'required|numeric|min:0',
            'status' => 'required|string',
            'total' => 'required|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
            'payment_status' => 'required|string',
            'coupon_code' => 'nullable|string',

            // Array based inputs from Admin Form
            'products' => 'required|array|min:1',
            'products.*' => 'exists:products,id',
            'prices' => 'required|array',
            'prices.*' => 'numeric|min:0',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if ($this->has('user_id') && $this->input('user_id') == 0) {
            $this->merge([
                'user_id' => null,
            ]);
        }
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'customer_name.required' => 'Customer name is required',
            'customer_email.required' => 'Customer email is required',
            'customer_email.email' => 'Please provide a valid email address',
            'customer_phone.required' => 'Phone number is required',
            'customer_phone.min' => 'Phone number must be at least 11 digits',
            'shipping_address.required' => 'Shipping address is required',
            'shipping_address.min' => 'Shipping address must be at least 5 characters',
            'city.required' => 'Please select a city/district',
            'area.required' => 'Please select an area',
            'payment_method.required' => 'Please select a payment method',
            'payment_method.in' => 'Invalid payment method selected',
            'shipping_method.required' => 'Shipping method is required',
            'shipping_cost.required' => 'Shipping cost is required',
            'items.required' => 'Order must contain at least one item',
            'items.min' => 'Order must contain at least one item',
            'items.*.product_id.required' => 'Product ID is missing in one of the items',
            'items.*.product_id.integer' => 'Product ID must be a number',
            'items.*.quantity.required' => 'Item quantity is required',
            'items.*.quantity.min' => 'Quantity must be at least 1',
            'items.*.quantity.max' => 'Quantity cannot exceed 1000',
            'items.*.price.required' => 'Item price is required',
            'items.*.price.numeric' => 'Item price must be a number',
        ];
    }
}
