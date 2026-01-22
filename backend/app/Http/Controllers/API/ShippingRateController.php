<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ShippingCalculator;
use Illuminate\Http\Request;

class ShippingRateController extends Controller
{
    /**
     * Calculate shipping cost for given location
     * POST /api/shipping/calculate
     */
    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'district' => 'required|string',
            'area' => 'required|string',
        ]);

        $cost = ShippingCalculator::getShippingCost(
            $validated['district'],
            $validated['area']
        );

        $method = ShippingCalculator::getShippingMethod($validated['district']);

        return response()->json([
            'success' => true,
            'shipping_cost' => $cost,
            'shipping_method' => $method,
            'district' => $validated['district'],
            'area' => $validated['area'],
        ]);
    }
}
