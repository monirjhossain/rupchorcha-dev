<?php

namespace App\Services;

use App\Models\ShippingZone;
use App\Models\ShippingMethod;

class ShippingCalculator
{
    /**
     * Calculate shipping cost dynamically from ShippingZone database.
     * Falls back to default if zone not found.
     * 
     * @param string $district
     * @param string $area
     * @return int
     */
    public static function getShippingCost(string $district, string $area): int
    {
        try {
            // First try to find by exact zone name (region)
            $zone = ShippingZone::where('region', $area)
                ->orWhere('region', $district)
                ->first();
            
            if ($zone) {
                // Try to get the cost from associated shipping method
                $method = $zone->shippingMethods()->first();
                if ($method && $method->cost) {
                    return (int)$method->cost;
                }
            }
            
            // Fallback to default rates based on location
            if ($district === 'Dhaka' && $area === 'Dhaka Sadar') {
                return 60;
            }
            
            return 120;
        } catch (\Exception $e) {
            // On any error, return default rate
            return 120;
        }
    }

    /**
     * Determine shipping method based on district.
     * 
     * @param string $district
     * @return string
     */
    public static function getShippingMethod(string $district): string
    {
        try {
            $zone = ShippingZone::where('region', $district)->first();
            if ($zone) {
                $method = $zone->shippingMethods()->first();
                if ($method) {
                    return $method->name ?? 'standard';
                }
            }
        } catch (\Exception $e) {
            // Silent fail
        }
        
        return $district === 'Dhaka' ? 'inside_dhaka' : 'outside_dhaka';
    }

    /**
     * Get all shipping zones with their rates
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAllZones()
    {
        try {
            return ShippingZone::with('shippingMethods')->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }
}
