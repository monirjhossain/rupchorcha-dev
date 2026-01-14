<?php
namespace App\Http\Controllers\API;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    // Get products by brand slug
    public function productsBySlug($slug)
    {
        $brand = Brand::whereRaw('LOWER(slug) = ?', [strtolower($slug)])->first();
        if (!$brand) {
            return response()->json(['success' => false, 'message' => 'Brand not found.'], 404);
        }
        $products = $brand->products()->with(['brand', 'images'])->get();
        return response()->json(['success' => true, 'products' => $products]);
    }
    // List all brands
    public function index()
    {
        $brands = Brand::withCount('products')->get();
        return response()->json(['success' => true, 'brands' => $brands]);
    }

    // Show brand details
    public function show($id)
    {
        $brand = Brand::findOrFail($id);
        return response()->json(['success' => true, 'brand' => $brand]);
    }

    // Create brand (admin)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $brand = Brand::create($request->all());
        return response()->json(['success' => true, 'message' => 'Brand added.', 'brand' => $brand]);
    }

    // Update brand (admin)
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
        ]);
        $brand->update($request->all());
        return response()->json(['success' => true, 'message' => 'Brand updated.', 'brand' => $brand]);
    }

    // Delete brand (admin)
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();
        return response()->json(['success' => true, 'message' => 'Brand deleted.']);
    }
}
