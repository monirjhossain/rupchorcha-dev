<?php
namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    // List all products
    public function index(Request $request)
    {
        $query = Product::with('brand');

        // Filter by name (search)
        if ($request->has('name') && !empty(trim($request->name))) {
            $name = trim($request->name);
            $name = mb_strtolower($name, 'UTF8');
            $query->where(function($q) use ($name) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . $name . '%'])
                  ->orWhereRaw('LOWER(short_description) LIKE ?', ['%' . $name . '%'])
                  ->orWhereRaw('LOWER(description) LIKE ?', ['%' . $name . '%'])
                  ->orWhereRaw('LOWER(sku) LIKE ?', ['%' . $name . '%']);
            });
        }

        // Filter by category if provided
        if ($request->has('categories')) {
            $categoryIds = explode(',', $request->categories);
            $query->whereIn('category_id', $categoryIds);
        }

        // Filter by price range if provided
        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }
        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        $products = $query->paginate(20);
        // Always return as array for frontend compatibility
        return response()->json([
            'success' => true,
            'products' => $products->toArray()
        ]);
    }

    // Show product details
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json(['success' => true, 'product' => $product]);
    }

    // Create product (admin)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|integer|exists:categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
        ]);
        $product = Product::create($request->all());
        return response()->json(['success' => true, 'message' => 'Product added.', 'product' => $product]);
    }

    // Update product (admin)
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'price' => 'numeric',
            'stock' => 'integer',
            'category_id' => 'integer|exists:categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
        ]);
        $product->update($request->all());
        return response()->json(['success' => true, 'message' => 'Product updated.', 'product' => $product]);
    }

    // Delete product (admin)
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['success' => true, 'message' => 'Product deleted.']);
    }
}
