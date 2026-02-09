<?php
namespace App\Http\Controllers\API;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    // Get products by brand slug
    public function productsBySlug(Request $request, $slug)
    {
        $brand = Brand::whereRaw('LOWER(slug) = ?', [strtolower($slug)])->first();
        if (!$brand) {
            return response()->json(['success' => false, 'message' => 'Brand not found.'], 404);
        }
        
        $query = $brand->products()->with(['brand', 'images']);
        
        // Handle sorting
        $sort = $request->input('sort', 'default');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('id', 'desc');
                break;
        }
        
        $perPage = $request->input('per_page', 12);
        $products = $query->paginate($perPage);
        
        return response()->json(['success' => true, 'brand' => $brand, 'products' => $products]);
    }
    // List all brands
    public function index()
    {
        $brands = Brand::withCount('products')->get();
        return response()->json(['success' => true, 'data' => \App\Http\Resources\BrandResource::collection($brands)]);
    }

    // Show brand details
    public function show($id)
    {
        $brand = Brand::findOrFail($id);
        return response()->json(['success' => true, 'data' => new \App\Http\Resources\BrandResource($brand)]);
    }

    // Create brand (admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('brands/banners', 'public');
        }
        $brand = Brand::create($validated);
        return response()->json(['success' => true, 'message' => 'Brand added.', 'brand' => $brand]);
    }

    // Update brand (admin)
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        $validated = $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('brands/banners', 'public');
        }
        $brand->update($validated);
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
