<?php

namespace App\Http\Controllers\API;

use App\Services\CouponService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CouponController extends Controller
{
    /**
     * Validate a coupon code and return discount amount
     * POST /api/coupons/validate
     */
    public function validateCoupon(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'integer',
        ]);

        $userId = auth()->id();
        
        $result = CouponService::validateCoupon(
            $validated['code'],
            $validated['subtotal'],
            $userId,
            $validated['product_ids'] ?? []
        );

        return response()->json($result);
    }
}
