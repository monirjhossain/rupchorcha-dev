<?php
namespace App\Http\Controllers;

use App\Models\DiscountCondition;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class DiscountConditionController extends Controller
{
    public function index()
    {
        $conditions = DiscountCondition::latest()->get();
        return view('admin.discount_conditions.index', compact('conditions'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        return view('admin.discount_conditions.create', compact('products', 'brands', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:product,brand,category',
            'target_id' => 'required|integer',
            'discount_type' => 'required|in:percentage,fixed,free_shipping',
            'discount_value' => 'nullable|numeric',
            'free_shipping' => 'boolean',
            'notes' => 'nullable|string',
        ]);
        if ($data['discount_type'] === 'free_shipping') {
            $data['free_shipping'] = true;
            $data['discount_value'] = null;
        }
        DiscountCondition::create($data);
        return redirect()->route('discount-conditions.index')->with('success', 'Discount condition added!');
    }

    public function edit($id)
    {
        $condition = DiscountCondition::findOrFail($id);
        $products = Product::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        return view('admin.discount_conditions.edit', compact('condition', 'products', 'brands', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $condition = DiscountCondition::findOrFail($id);
        $data = $request->validate([
            'discount_type' => 'required|in:percentage,fixed,free_shipping',
            'discount_value' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);
        if ($data['discount_type'] === 'free_shipping') {
            $data['free_shipping'] = true;
            $data['discount_value'] = null;
        } else {
            $data['free_shipping'] = false;
        }
        $condition->update($data);
        return redirect()->route('discount-conditions.index')->with('success', 'Discount condition updated!');
    }

    public function destroy($id)
    {
        DiscountCondition::findOrFail($id)->delete();
        return redirect()->route('discount-conditions.index')->with('success', 'Discount condition deleted!');
    }
}
