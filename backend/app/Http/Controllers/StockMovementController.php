<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockMovementController extends Controller
{
    // Show stock movement form for a product
    public function create($productId)
    {
        $product = Product::findOrFail($productId);
        return view('admin.products.stock_movement', compact('product'));
    }

    // Store a stock movement (in, out, adjustment)
    public function store(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $validated = $request->validate([
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer',
            'reason' => 'nullable|string|max:255',
        ]);
        // For 'out', quantity is negative
        $qty = $validated['type'] === 'out' ? -abs($validated['quantity']) : abs($validated['quantity']);
        if ($validated['type'] === 'adjustment') {
            $qty = $validated['quantity']; // can be positive or negative
        }
        StockMovement::create([
            'product_id' => $product->id,
            'type' => $validated['type'],
            'quantity' => $qty,
            'reason' => $validated['reason'] ?? null,
            'user_id' => Auth::id(),
        ]);
        return redirect()->route('products.edit', $product->id)->with('success', 'Stock movement recorded.');
    }
}
