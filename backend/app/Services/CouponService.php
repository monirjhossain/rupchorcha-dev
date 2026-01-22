<?php

namespace App\Services;

use App\Models\Coupon;
use Carbon\Carbon;

class CouponService
{
    /**
     * Validate and calculate coupon discount
     * 
     * @param string $code
     * @param float $subtotal
     * @param int|null $userId
     * @param array $productIds
     * @return array ['valid' => bool, 'discount' => float, 'message' => string]
     */
    public static function validateCoupon(
        string $code, 
        float $subtotal, 
        ?int $userId = null, 
        array $productIds = []
    ): array {
        // Find active coupon
        $coupon = Coupon::where('code', $code)
            ->where('active', true)
            ->first();

        if (!$coupon) {
            return [
                'valid' => false,
                'discount' => 0,
                'message' => 'Invalid coupon code'
            ];
        }

        // Check if coupon has started
        if ($coupon->start_at && Carbon::parse($coupon->start_at)->isFuture()) {
            return [
                'valid' => false,
                'discount' => 0,
                'message' => 'Coupon is not yet active'
            ];
        }

        // Check if coupon has expired
        if ($coupon->expires_at && Carbon::parse($coupon->expires_at)->isPast()) {
            return [
                'valid' => false,
                'discount' => 0,
                'message' => 'Coupon has expired'
            ];
        }

        // Check minimum order amount
        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            return [
                'valid' => false,
                'discount' => 0,
                'message' => "Minimum order amount is ৳{$coupon->min_order_amount}"
            ];
        }

        // Check usage limit
        if ($coupon->usage_limit) {
            // Count total usage (would need a coupon_usage tracking table for production)
            // For now, simplified check
        }

        // Check product restrictions
        if ($coupon->product_ids && count($coupon->product_ids) > 0) {
            $hasValidProduct = false;
            foreach ($productIds as $pid) {
                if (in_array($pid, $coupon->product_ids)) {
                    $hasValidProduct = true;
                    break;
                }
            }
            if (!$hasValidProduct) {
                return [
                    'valid' => false,
                    'discount' => 0,
                    'message' => 'Coupon not applicable for these products'
                ];
            }
        }

        // Calculate discount
        $discount = 0;
        
        if ($coupon->type === 'percent') {
            $discount = ($subtotal * $coupon->value) / 100;
            
            // Apply max discount limit
            if ($coupon->max_discount && $discount > $coupon->max_discount) {
                $discount = $coupon->max_discount;
            }
        } elseif ($coupon->type === 'fixed') {
            $discount = min($coupon->value, $subtotal); // Don't exceed subtotal
        }

        return [
            'valid' => true,
            'discount' => round($discount, 2),
            'message' => 'Coupon applied successfully',
            'coupon' => $coupon
        ];
    }
}
