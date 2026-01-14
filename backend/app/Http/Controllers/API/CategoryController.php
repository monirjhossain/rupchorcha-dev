<?php
namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    // List all categories
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return response()->json(['success' => true, 'categories' => $categories]);
    }

    // Show category details
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json(['success' => true, 'category' => $category]);
    }

    // Create category (admin)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $category = Category::create($request->all());
        return response()->json(['success' => true, 'message' => 'Category added.', 'category' => $category]);
    }

    // Update category (admin)
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
        ]);
        $category->update($request->all());
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
