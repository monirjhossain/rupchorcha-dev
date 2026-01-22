<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DebugController extends Controller
{
    public function orderSchema()
    {
        return response()->json([
            'required_fields' => [
                'customer_name' => ['type' => 'string', 'max' => 255],
                'customer_email' => ['type' => 'email', 'max' => 255],
                'customer_phone' => ['type' => 'string', 'min' => 11, 'max' => 20],
                'shipping_address' => ['type' => 'string', 'min' => 5],
                'city' => ['type' => 'string', 'max' => 100],
                'area' => ['type' => 'string', 'max' => 100],
                'payment_method' => ['type' => 'enum', 'values' => ['cod', 'bkash', 'nagad']],
                'shipping_method' => ['type' => 'string', 'max' => 100],
                'shipping_cost' => ['type' => 'numeric', 'min' => 0],
                'items' => [
                    'type' => 'array',
                    'min' => 1,
                    'items' => [
                        'product_id' => ['type' => 'integer', 'min' => 1],
                        'quantity' => ['type' => 'integer', 'min' => 1],
                        'price' => ['type' => 'numeric', 'min' => 0],
                    ]
                ],
            ],
            'optional_fields' => [
                'notes' => ['type' => 'string', 'max' => 500],
                'coupon_code' => ['type' => 'string', 'max' => 100],
                'discount_amount' => ['type' => 'numeric', 'min' => 0],
            ],
        ], 200);
    }

    public function validateOrderPayload(Request $request)
    {
        $payload = $request->all();
        $issues = [];

        // Check required fields
        $requiredFields = ['customer_name', 'customer_email', 'customer_phone', 'shipping_address', 'city', 'area', 'payment_method', 'shipping_method', 'items'];
        foreach ($requiredFields as $field) {
            if (!isset($payload[$field]) || (is_string($payload[$field]) && empty(trim($payload[$field])))) {
                $issues[] = "Missing required field: $field";
            }
        }

        // Check items array
        if (isset($payload['items'])) {
            if (!is_array($payload['items']) || empty($payload['items'])) {
                $issues[] = "Items must be a non-empty array";
            } else {
                foreach ($payload['items'] as $i => $item) {
                    if (!isset($item['product_id']) || !is_int($item['product_id'])) {
                        $issues[] = "Item $i: product_id is required and must be an integer";
                    }
                    if (!isset($item['quantity']) || !is_int($item['quantity']) || $item['quantity'] < 1) {
                        $issues[] = "Item $i: quantity is required and must be >= 1";
                    }
                    if (!isset($item['price']) || !is_numeric($item['price']) || $item['price'] < 0) {
                        $issues[] = "Item $i: price is required and must be numeric >= 0";
                    }
                }
            }
        }

        // Check phone length
        if (isset($payload['customer_phone']) && strlen($payload['customer_phone']) < 11) {
            $issues[] = "Phone number must be at least 11 characters";
        }

        // Check payment method
        if (isset($payload['payment_method']) && !in_array($payload['payment_method'], ['cod', 'bkash', 'nagad'])) {
            $issues[] = "Invalid payment_method. Must be one of: cod, bkash, nagad";
        }

        return response()->json([
            'valid' => empty($issues),
            'issues' => $issues,
            'received_payload' => $payload,
        ], 200);
    }
}
