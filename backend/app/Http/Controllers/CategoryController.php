<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        $categories = \App\Models\Category::with('children', 'parent')->get();
        return view('admin.categories.index', compact('categories'));
    }
    public function create() { $categories = Category::all(); return view('admin.categories.create', compact('categories')); }
    public function store(Request $request) {
        $validated = $request->validate([
            'name'=>'required',
            'slug'=>'required|unique:categories',
            'parent_id'=>'nullable|exists:categories,id',
            'description'=>'nullable|string',
        ]);
        Category::create($validated);
        return redirect()->route('categories.index')->with('success','Category created.');
    }
    public function edit($id) { $category = Category::findOrFail($id); $categories = Category::where('id', '!=', $id)->get(); return view('admin.categories.edit', compact('category', 'categories')); }
    public function update(Request $request, $id) {
        $category = Category::findOrFail($id);
        $validated = $request->validate([
            'name'=>'required',
            'slug'=>'required|unique:categories,slug,'.$category->id,
            'parent_id'=>'nullable|exists:categories,id',
            'description'=>'nullable|string',
        ]);
        $category->update($validated);
        return redirect()->route('categories.index')->with('success','Category updated.');
    }
    public function destroy($id) { $category = Category::findOrFail($id); $category->delete(); return redirect()->route('categories.index')->with('success','Category deleted.'); }
}
