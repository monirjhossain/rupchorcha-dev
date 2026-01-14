<?php

namespace App\Http\Controllers;

use App\Models\ShippingZone;
use Illuminate\Http\Request;

class ShippingZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $zones = ShippingZone::with('shippingMethods')->get();
        return view('admin.shipping_zones.index', compact('zones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ShippingZone $shippingZone)
    {
        $zone = $shippingZone->load('shippingMethods');
        return view('admin.shipping_zones.show', compact('zone'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShippingZone $shippingZone)
    {
        $zone = $shippingZone;
        return view('admin.shipping_zones.edit', compact('zone'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShippingZone $shippingZone)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'region' => 'nullable|string|max:255',
            'postcode' => 'nullable|string|max:255',
        ]);
        $shippingZone->update($data);
        return redirect()->route('shipping-zones.index')->with('success', 'Shipping zone updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingZone $shippingZone)
    {
        //
    }

    /**
     * Add a shipping method to a zone
     */
    public function storeMethod(Request $request, ShippingZone $zone)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'cost' => 'required|numeric',
            'status' => 'required|boolean',
            'sort_order' => 'nullable|integer',
        ]);
        $method = \App\Models\ShippingMethod::create($data);
        $zone->shippingMethods()->attach($method->id);
        return redirect()->route('shipping-zones.show', $zone)->with('success', 'Shipping method added to zone.');
    }
}
