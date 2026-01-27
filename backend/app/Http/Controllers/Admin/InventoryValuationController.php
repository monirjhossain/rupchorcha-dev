<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryValuationController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::select('id', 'name', 'sku', 'stock_quantity', 'cost_price')
            ->orderBy('name')
            ->get();
        $totalValue = $products->sum(function ($product) {
            return ($product->cost_price ?? 0) * ($product->stock_quantity ?? 0);
        });
        return view('admin.inventory.valuation', compact('products', 'totalValue'));
    }
}
