<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // Inventory dashboard
    public function index(Request $request)
    {
        $query = Product::query();
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'in_stock') {
                $query->whereHas('stockMovements', function($q) {
                    $q->havingRaw('SUM(quantity) > 0');
                });
            } elseif ($request->stock_status === 'out_of_stock') {
                $query->whereDoesntHave('stockMovements')->orWhereHas('stockMovements', function($q) {
                    $q->havingRaw('SUM(quantity) <= 0');
                });
            }
        }
        $products = $query->with('stockMovements')->paginate(15)->appends($request->except('page'));
        return view('admin.inventory.index', compact('products'));
    }
}
