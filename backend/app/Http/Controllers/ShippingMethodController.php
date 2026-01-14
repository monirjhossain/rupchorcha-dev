<?php

namespace App\Http\Controllers;

use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class ShippingMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shippingMethods = ShippingMethod::orderBy('sort_order')->get();
        return view('admin.shipping_methods.index', compact('shippingMethods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.shipping_methods.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'cost' => 'required|numeric',
            'min_order' => 'nullable|numeric',
            'max_order' => 'nullable|numeric',
            'status' => 'required|boolean',
            'sort_order' => 'nullable|integer',
        ]);
        ShippingMethod::create($data);
        return redirect()->route('shipping-methods.index')->with('success', 'Shipping method created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShippingMethod $shippingMethod)
    {
        $shippingMethod->load('conditions');
        return view('admin.shipping_methods.show', compact('shippingMethod'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShippingMethod $shippingMethod)
    {
        return view('admin.shipping_methods.edit', compact('shippingMethod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShippingMethod $shippingMethod)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'cost' => 'required|numeric',
            'min_order' => 'nullable|numeric',
            'max_order' => 'nullable|numeric',
            'status' => 'required|boolean',
            'sort_order' => 'nullable|integer',
        ]);
        $shippingMethod->update($data);
        return redirect()->route('shipping-methods.index')->with('success', 'Shipping method updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingMethod $shippingMethod)
    {
        $shippingMethod->delete();
        return redirect()->route('shipping-methods.index')->with('success', 'Shipping method deleted successfully.');
    }

    /**
     * Show conditions UI
     */
    public function conditions(ShippingMethod $method)
    {
        $method->load('conditions');
        return view('admin.shipping_methods.conditions', compact('method'));
    }

    /**
     * Store a new condition
     */
    public function storeCondition(Request $request, ShippingMethod $method)
    {
        $data = $request->validate([
            'condition_type' => 'required|string',
            'condition_value' => 'required|string',
        ]);
        $method->conditions()->create($data);
        return redirect()->route('shipping-methods.conditions', $method)->with('success', 'Condition added.');
    }

    /**
     * Delete a condition
     */
    public function destroyCondition(ShippingMethod $method, $conditionId)
    {
        $method->conditions()->where('id', $conditionId)->delete();
        return redirect()->route('shipping-methods.conditions', $method)->with('success', 'Condition deleted.');
    }
}
