<?php
namespace App\Http\Controllers\API;

use App\Models\Discount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DiscountController extends Controller
{
    // List all discounts
    public function index()
    {
        $discounts = Discount::all();
        return response()->json(['success' => true, 'discounts' => $discounts]);
    }

    // Show discount details
    public function show($id)
    {
        $discount = Discount::findOrFail($id);
        return response()->json(['success' => true, 'discount' => $discount]);
    }

    // Create discount (admin)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'discount_type' => 'required|string',
            'discount_value' => 'required|numeric',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date',
            'active' => 'required|boolean',
        ]);
        $discount = Discount::create($request->all());
        return response()->json(['success' => true, 'message' => 'Discount added.', 'discount' => $discount]);
    }

    // Update discount (admin)
    public function update(Request $request, $id)
    {
        $discount = Discount::findOrFail($id);
        $request->validate([
            'name' => 'string|max:255',
            'discount_type' => 'string',
            'discount_value' => 'numeric',
            'valid_from' => 'date',
            'valid_to' => 'date',
            'active' => 'boolean',
        ]);
        $discount->update($request->all());
        return response()->json(['success' => true, 'message' => 'Discount updated.', 'discount' => $discount]);
    }

    // Delete discount (admin)
    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();
        return response()->json(['success' => true, 'message' => 'Discount deleted.']);
    }
}
