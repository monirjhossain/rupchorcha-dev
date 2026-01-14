<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public function index() { $images = ProductImage::with('product')->get(); return view('admin.product_images.index', compact('images')); }
    public function create() { return view('admin.product_images.create'); }
    public function store(Request $request) { $validated = $request->validate(['product_id'=>'required|exists:products,id','image_path'=>'required']); ProductImage::create($validated); return redirect()->route('product_images.index')->with('success','Image added.'); }
    public function destroy($id) { $image = ProductImage::findOrFail($id); $image->delete(); return redirect()->route('product_images.index')->with('success','Image deleted.'); }
}
