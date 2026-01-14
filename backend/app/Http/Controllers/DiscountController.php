<?php
namespace App\Http\Controllers;
use App\Models\Discount;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
class DiscountController extends Controller {
    public function index() {
        $discounts = Discount::orderByDesc('id')->paginate(20);
        return view('admin.discounts.index', compact('discounts'));
    }
    public function create() {
        $products = Product::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        return view('admin.discounts.create', compact('products', 'categories', 'brands'));
    }
    public function store(Request $request) {
        $data = $request->validate([
            'title' => 'required|string',
            'type' => 'required|string',
            'product_ids' => 'array',
            'combo_product_ids' => 'array',
            'category_ids' => 'array',
            'brand_ids' => 'array',
            'discount_value' => 'nullable|numeric',
            'discount_type' => 'required|string',
            'min_quantity' => 'nullable|integer',
            'start_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
            'active' => 'boolean',
        ]);
        Discount::create($data);
        return redirect()->route('discounts.index')->with('success', 'Discount created successfully.');
    }
    public function edit(Discount $discount) {
        $products = Product::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        return view('admin.discounts.edit', compact('discount', 'products', 'categories', 'brands'));
    }
    public function update(Request $request, Discount $discount) {
        $data = $request->validate([
            'title' => 'required|string',
            'type' => 'required|string',
            'product_ids' => 'array',
            'combo_product_ids' => 'array',
            'category_ids' => 'array',
            'brand_ids' => 'array',
            'discount_value' => 'nullable|numeric',
            'discount_type' => 'required|string',
            'min_quantity' => 'nullable|integer',
            'start_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
            'active' => 'boolean',
        ]);
        $discount->update($data);
        return redirect()->route('discounts.index')->with('success', 'Discount updated successfully.');
    }
    public function destroy(Discount $discount) {
        $discount->delete();
        return redirect()->route('discounts.index')->with('success', 'Discount deleted successfully.');
    }
}
