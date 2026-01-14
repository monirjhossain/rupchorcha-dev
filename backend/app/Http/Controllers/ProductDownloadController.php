<?php

namespace App\Http\Controllers;

use App\Models\ProductDownload;
use Illuminate\Http\Request;

class ProductDownloadController extends Controller
{
    public function index() { $downloads = ProductDownload::with('product')->get(); return view('admin.product_downloads.index', compact('downloads')); }
    public function create() { return view('admin.product_downloads.create'); }
    public function store(Request $request) { $validated = $request->validate(['product_id'=>'required|exists:products,id','file_name'=>'required','file_path'=>'required']); ProductDownload::create($validated); return redirect()->route('product_downloads.index')->with('success','Download added.'); }
    public function destroy($id) { $download = ProductDownload::findOrFail($id); $download->delete(); return redirect()->route('product_downloads.index')->with('success','Download deleted.'); }
}
