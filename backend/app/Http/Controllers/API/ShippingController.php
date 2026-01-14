<?php
namespace App\Http\Controllers\API;

use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShippingController extends Controller
{
    // List all shipping methods
    public function methods()
    {
        $methods = ShippingMethod::all();
        return response()->json(['success' => true, 'methods' => $methods]);
    }

    // Add shipping method (admin)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cost' => 'required|numeric',
            'description' => 'nullable|string',
        ]);
        $method = ShippingMethod::create($request->all());
        return response()->json(['success' => true, 'message' => 'Shipping method added.', 'method' => $method]);
    }

    // Update shipping method (admin)
    public function update(Request $request, $id)
    {
        $method = ShippingMethod::findOrFail($id);
        $request->validate([
            'name' => 'string|max:255',
            'cost' => 'numeric',
            'description' => 'nullable|string',
        ]);
        $method->update($request->all());
        return response()->json(['success' => true, 'message' => 'Shipping method updated.', 'method' => $method]);
    }

    // Delete shipping method (admin)
    public function destroy($id)
    {
        $method = ShippingMethod::findOrFail($id);
        $method->delete();
        return response()->json(['success' => true, 'message' => 'Shipping method deleted.']);
    }

    // Calculate shipping cost
    public function calculate(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'cart_id' => 'required|integer',
        ]);
        // Dummy calculation
        $cost = 100; // Replace with actual logic
        return response()->json(['success' => true, 'cost' => $cost, 'method' => 'Standard']);
    }
}
