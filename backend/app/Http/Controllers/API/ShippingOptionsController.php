<?php

namespace App\Http\Controllers\API;

use App\Models\ShippingZone;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShippingOptionsController extends Controller
{
    /**
     * Get all active shipping zones with their methods
     * GET /api/shipping/zones-with-methods
     */
    public function getZonesWithMethods()
    {
        $zones = ShippingZone::with(['shippingMethods' => function($query) {
            $query->where('status', true)->orderBy('sort_order');
        }])->get();

        return response()->json([
            'success' => true,
            'zones' => $zones
        ]);
    }

    /**
     * Get shipping methods for a specific zone by district/area
     * POST /api/shipping/methods-by-location
     */
    public function getMethodsByLocation(Request $request)
    {
        $validated = $request->validate([
            'district' => 'required|string',
            'area' => 'nullable|string',
            'cart_total' => 'nullable|numeric', // For free shipping eligibility
        ]);

        // Determine zone based on district
        $zoneName = ($validated['district'] === 'Dhaka') ? 'Dhaka' : 'Outside Dhaka';
        
        $zone = ShippingZone::where('name', $zoneName)
            ->with(['shippingMethods' => function($query) {
                $query->where('status', true)->orderBy('sort_order');
            }])
            ->first();

        if (!$zone) {
            return response()->json([
                'success' => false,
                'message' => 'No shipping zone found for this location'
            ], 404);
        }

        $cartTotal = $validated['cart_total'] ?? 0;

        // Apply area-specific logic for Dhaka and filter based on conditions
        $methods = $zone->shippingMethods->filter(function($method) use ($cartTotal) {
            // Free delivery only shows if min_order condition is met
            if ($method->type === 'free') {
                return $method->min_order && $cartTotal >= $method->min_order;
            }
            // Other methods always show
            return true;
        })->map(function($method) use ($validated) {
            $cost = $method->cost;
            
            // Dhaka shipping logic:
            // - Dhaka Sadar: 60 tk
            // - Other Dhaka areas: 120 tk
            if ($validated['district'] === 'Dhaka' && $method->type === 'flat') {
                if (isset($validated['area']) && $validated['area'] === 'Dhaka Sadar') {
                    $cost = 60; // Dhaka Sadar special rate
                } else {
                    $cost = 120; // Other Dhaka areas
                }
            }
            
            return [
                'id' => $method->id,
                'name' => $method->name,
                'type' => $method->type,
                'cost' => (float) $cost,
                'min_order' => $method->min_order ? (float) $method->min_order : null,
                'max_order' => $method->max_order ? (float) $method->max_order : null,
                'is_eligible' => true, // Already filtered
            ];
        })->values();

        return response()->json([
            'success' => true,
            'zone' => [
                'id' => $zone->id,
                'name' => $zone->name,
                'region' => $zone->region,
            ],
            'methods' => $methods,
            'location' => [
                'district' => $validated['district'],
                'area' => $validated['area'] ?? null,
            ]
        ]);
    }

    /**
     * Get all active shipping methods
     * GET /api/shipping/methods
     */
    public function getAllMethods()
    {
        $methods = ShippingMethod::where('status', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'methods' => $methods
        ]);
    }
}
