<?php
namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    // List all categories
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return response()->json(['success' => true, 'data' => CategoryResource::collection($categories)]);
    }

    // Show category details
    public function show($id)
    {
        $category = Category::with(['products.categories'])->findOrFail($id);
        
        return response()->json(['success' => true, 'data' => new CategoryResource($category)]);
    }

    // Create category (admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('categories/banners', 'public');
        }
        $category = Category::create($validated);
        return response()->json(['success' => true, 'message' => 'Category added.', 'data' => new CategoryResource($category)]);
    }

    // Update category (admin)
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $validated = $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('categories/banners', 'public');
        }
        $category->update($validated);
        return response()->json(['success' => true, 'message' => 'Category updated.', 'category' => $category]);
    }

    // Delete category (admin)
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['success' => true, 'message' => 'Category deleted.']);
    }
}
