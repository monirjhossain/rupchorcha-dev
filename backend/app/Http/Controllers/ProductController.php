<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // Display a listing of the products
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images', 'tags', 'attributes']);
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        // Advanced filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'in_stock') {
                $query->where('stock_quantity', '>', 0);
            } elseif ($request->stock_status === 'out_of_stock') {
                $query->where(function($q) {
                    $q->whereNull('stock_quantity')->orWhere('stock_quantity', '<=', 0);
                });
            }
        }
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }
        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }
        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }
        $products = $query->paginate(15)->appends($request->except('page'));
        $categories = \App\Models\Category::all();
        $brands = \App\Models\Brand::all();
        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    // Show the form for creating a new product
    public function create()
    {
        $tags = Tag::all();
        $categories = Category::all();
        $brands = Brand::all();
        $suppliers = \App\Models\Supplier::all();
        $warehouses = \App\Models\Warehouse::all();
        return view('admin.products.create', compact('tags', 'categories', 'brands', 'suppliers', 'warehouses'));
    }

    // Store a newly created product in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products',
            'description' => 'required',
            'short_description' => 'nullable|string',
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'cost_price' => 'nullable|numeric',
            'stock_quantity' => 'nullable|integer',
            'stock_in' => 'nullable|string',
            'sku' => 'nullable|string|max:255',
            'type' => 'required|string',
            'status' => 'nullable|string',
            'featured' => 'nullable|boolean',
            'barcode' => 'nullable|string',
            'manage_stock' => 'nullable|boolean',
            'external_url' => 'nullable|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'main_image' => 'nullable|file|image',
            'gallery_images.*' => 'nullable|file|image',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'min_order_qty' => 'nullable|integer',
            'max_order_qty' => 'nullable|integer',
            'sale_start_date' => 'nullable|date',
            'sale_end_date' => 'nullable|date',
        ]);
        // Handle main image upload
        if ($request->hasFile('main_image')) {
            $validated['main_image'] = $request->file('main_image')->store('products', 'public');
        }
        $product = Product::create($validated);
        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $galleryImage) {
                $path = $galleryImage->store('products', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }
        if ($request->has('tags')) {
            $product->tags()->sync($request->input('tags'));
        }
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    // Show the form for editing the specified product
    public function edit($id)
    {
        $product = Product::with('tags')->findOrFail($id);
        $tags = Tag::all();
        $categories = Category::all();
        $brands = Brand::all();
        $suppliers = \App\Models\Supplier::all();
        $warehouses = \App\Models\Warehouse::all();
        return view('admin.products.edit', compact('product', 'tags', 'categories', 'brands', 'suppliers', 'warehouses'));
    }

    // Update the specified product in storage
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'required',
            'short_description' => 'nullable|string',
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'cost_price' => 'nullable|numeric',
            'stock_quantity' => 'nullable|integer',
            'stock_in' => 'nullable|string',
            'sku' => 'nullable|string|max:255',
            'type' => 'required|string',
            'status' => 'nullable|string',
            'featured' => 'nullable|boolean',
            'barcode' => 'nullable|string',
            'manage_stock' => 'nullable|boolean',
            'external_url' => 'nullable|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'main_image' => 'nullable|file|image',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'min_order_qty' => 'nullable|integer',
            'max_order_qty' => 'nullable|integer',
            'sale_start_date' => 'nullable|date',
            'sale_end_date' => 'nullable|date',
        ]);
        // Handle image upload
        if ($request->hasFile('main_image')) {
            $validated['main_image'] = $request->file('main_image')->store('products', 'public');
        }
        $product->update($validated);
        if ($request->has('tags')) {
            $product->tags()->sync($request->input('tags'));
        } else {
            $product->tags()->detach();
        }
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    // Remove the specified product from storage
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

        // Export selected product fields as CSV
    public function export(Request $request)
    {
        $fields = $request->input('fields', []);
        if (empty($fields)) {
            return back()->with('error', 'Please select at least one field to export.');
        }
        $products = Product::with(['category', 'brand'])->get();
        $csvData = [];
        // Prepare header
        $header = [];
        foreach ($fields as $field) {
            $header[] = ucfirst(str_replace('_', ' ', $field));
        }
        $csvData[] = $header;
        // Prepare rows
        foreach ($products as $product) {
            $row = [];
            foreach ($fields as $field) {
                if ($field === 'category') {
                    $row[] = $product->category->name ?? '';
                } elseif ($field === 'brand') {
                    $row[] = $product->brand->name ?? '';
                } else {
                    $row[] = $product->$field;
                }
            }
            $csvData[] = $row;
        }
        // Output CSV
        $filename = 'products_export_' . date('Ymd_His') . '.csv';
        $handle = fopen('php://temp', 'r+');
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename"
        ]);
    }

    public function bulkImport()
    {
        set_time_limit(0); // Allow unlimited execution time for large imports
        request()->validate([
            'import_file' => 'required|file|mimes:csv,txt',
        ]);
        $file = request()->file('import_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);
        $imported = 0;
        $fields = ['name','sku','price','sale_price','cost_price','category','brand','stock_quantity','main_image','type'];
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            $productData = array_intersect_key($data, array_flip($fields));
            // Convert category and brand names to IDs
            if (!empty($productData['category'])) {
                $category = \App\Models\Category::where('name', $productData['category'])->first();
                $productData['category_id'] = $category ? $category->id : null;
            }
            if (!empty($productData['brand'])) {
                $brand = \App\Models\Brand::where('name', $productData['brand'])->first();
                $productData['brand_id'] = $brand ? $brand->id : null;
            }
            // Download image from URL if main_image is a URL
            if (!empty($productData['main_image'])) {
                // If multiple URLs, use only the first one
                if (strpos($productData['main_image'], ',') !== false) {
                    $urls = explode(',', $productData['main_image']);
                    $productData['main_image'] = trim($urls[0]);
                }
                if (filter_var($productData['main_image'], FILTER_VALIDATE_URL)) {
                    try {
                        $imageContents = @file_get_contents($productData['main_image']);
                        if ($imageContents !== false) {
                            $ext = pathinfo(parse_url($productData['main_image'], PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                            $filename = 'products/' . Str::random(20) . '.' . $ext;
                            Storage::disk('public')->put($filename, $imageContents);
                            $productData['main_image'] = $filename;
                        } else {
                            unset($productData['main_image']); // Skip if download fails
                        }
                    } catch (\Exception $e) {
                        unset($productData['main_image']); // Skip if error
                    }
                }
            }
            // Only require: name, sku, price
            if (!empty($productData['name']) && !empty($productData['sku']) && !empty($productData['price'])) {
                // Set defaults for missing fields
                if (empty($productData['type'])) {
                    $productData['type'] = 'simple';
                }
                if (!isset($productData['stock_quantity']) || $productData['stock_quantity'] === '') {
                    $productData['stock_quantity'] = 0;
                }
                if (!isset($productData['category_id'])) {
                    $productData['category_id'] = null;
                }
                if (!isset($productData['brand_id'])) {
                    $productData['brand_id'] = null;
                }
                if (!isset($productData['description']) || empty($productData['description'])) {
                    $productData['description'] = '-';
                }
                Product::updateOrCreate([
                    'sku' => $productData['sku']
                ], $productData);
                $imported++;
            }
        }
        fclose($handle);
        return redirect()->route('products.index')->with('success', "$imported products imported successfully.");
    }

    public function bulkImportSample()
    {
        $fields = ['name','sku','price','sale_price','cost_price','category','brand','stock_quantity','main_image','type'];
        $sample = [
            [
                'name' => 'Sample Product 1',
                'sku' => 'SKU001',
                'price' => '100',
                'sale_price' => '90',
                'cost_price' => '80',
                'category' => 'Electronics',
                'brand' => 'Apple',
                'stock_quantity' => '50',
                'main_image' => 'sample1.jpg',
                'type' => 'simple',
            ],
            [
                'name' => 'Sample Product 2',
                'sku' => 'SKU002',
                'price' => '200',
                'sale_price' => '',
                'cost_price' => '',
                'category' => 'Fashion',
                'brand' => 'Nike',
                'stock_quantity' => '',
                'main_image' => '',
                'type' => 'simple',
            ],
        ];
        $filename = 'product_import_sample.csv';
        $handle = fopen('php://memory', 'w');
        fputcsv($handle, $fields);
        foreach ($sample as $row) {
            fputcsv($handle, $row);
        }
        fseek($handle, 0);
        return response()->streamDownload(function() use ($handle) {
            fpassthru($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function showBulkImportForm()
    {
        return view('admin.products.bulk-import');
    }
}
