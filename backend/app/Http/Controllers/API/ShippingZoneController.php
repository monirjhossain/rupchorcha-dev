<?php
namespace App\Http\Controllers\API;

use App\Models\ShippingZone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShippingZoneController extends Controller
{
    // List all shipping zones
    public function index()
    {
        $zones = ShippingZone::all();
        return response()->json(['success' => true, 'zones' => $zones]);
    }

    // Show shipping zone details
    public function show($id)
    {
        $zone = ShippingZone::findOrFail($id);
        return response()->json(['success' => true, 'zone' => $zone]);
    }

    // Create shipping zone (admin)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'regions' => 'required|array',
            'cost' => 'required|numeric',
        ]);
        $zone = ShippingZone::create([
            'name' => $request->name,
            'regions' => json_encode($request->regions),
            'cost' => $request->cost,
        ]);
        return response()->json(['success' => true, 'message' => 'Shipping zone added.', 'zone' => $zone]);
    }

    // Update shipping zone (admin)
    public function update(Request $request, $id)
    {
        $zone = ShippingZone::findOrFail($id);
        $request->validate([
            'name' => 'string|max:255',
            'regions' => 'array',
            'cost' => 'numeric',
        ]);
        $zone->update([
            'name' => $request->name ?? $zone->name,
            'regions' => $request->regions ? json_encode($request->regions) : $zone->regions,
            'cost' => $request->cost ?? $zone->cost,
        ]);
        return response()->json(['success' => true, 'message' => 'Shipping zone updated.', 'zone' => $zone]);
    }

    // Delete shipping zone (admin)
    public function destroy($id)
    {
        $zone = ShippingZone::findOrFail($id);
        $zone->delete();
        return response()->json(['success' => true, 'message' => 'Shipping zone deleted.']);
    }
}
