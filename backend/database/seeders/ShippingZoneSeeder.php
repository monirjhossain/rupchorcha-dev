<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingZone;
use App\Models\ShippingMethod;

class ShippingZoneSeeder extends Seeder
{
    public function run()
    {
        // Create zones
        $dhaka = ShippingZone::create([
            'name' => 'Dhaka',
            'country' => 'Bangladesh',
            'region' => 'Dhaka',
        ]);
        $outsideDhaka = ShippingZone::create([
            'name' => 'Outside Dhaka',
            'country' => 'Bangladesh',
            'region' => null,
        ]);

        // Create shipping methods for Dhaka
        $flatDhaka = ShippingMethod::create([
            'name' => 'Flat Rate',
            'type' => 'flat',
            'cost' => 60,
            'status' => true,
            'sort_order' => 1,
        ]);
        $freeDhaka = ShippingMethod::create([
            'name' => 'Free Delivery',
            'type' => 'free',
            'cost' => 0,
            'status' => true,
            'sort_order' => 2,
        ]);
        // Attach to zone
        $dhaka->shippingMethods()->attach([$flatDhaka->id, $freeDhaka->id]);

        // Create shipping methods for Outside Dhaka
        $flatOutside = ShippingMethod::create([
            'name' => 'Flat Rate',
            'type' => 'flat',
            'cost' => 120,
            'status' => true,
            'sort_order' => 1,
        ]);
        $freeOutside = ShippingMethod::create([
            'name' => 'Free Delivery',
            'type' => 'free',
            'cost' => 0,
            'status' => true,
            'sort_order' => 2,
        ]);
        // Attach to zone
        $outsideDhaka->shippingMethods()->attach([$flatOutside->id, $freeOutside->id]);
    }
}
