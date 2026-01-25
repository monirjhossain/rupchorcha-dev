<?php
namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    // ...existing code...
    // Show product details by slug
    public function showBySlug($slug)
    {
        $product = Product::with(['brand', 'images', 'categories', 'tags'])->where('slug', $slug)->first();
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        // Prepare images array (main_image first, then gallery images)
        $images = [];
        if ($product->main_image) {
            $images[] = url('storage/' . ltrim($product->main_image, '/'));
        }
        if ($product->images && count($product->images) > 0) {
            foreach ($product->images as $img) {
                $imgUrl = url('storage/' . ltrim($img->image_path, '/'));
                // Avoid duplicate if main_image is also in gallery
                if (!in_array($imgUrl, $images)) {
                    $images[] = $imgUrl;
                }
            }
        }
        $data = $product->toArray();
        $data['images'] = $images;
        $data['main_image_url'] = $product->main_image ? url('storage/' . ltrim($product->main_image, '/')) : null;
        // Add categories info
        $data['categories'] = $product->categories->map(function($cat) {
            return [
                'id' => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug,
            ];
        });
        // Add tags info
        $data['tags'] = $product->tags->map(function($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
            ];
        });
        return response()->json(['success' => true, 'product' => $data]);
    }
    // List all products
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'images', 'categories']);

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

        // Filter by categories (many-to-many)
        if ($request->has('categories')) {
            $categoryIds = explode(',', $request->categories);
            $query->whereHas('categories', function($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }

        // Filter by price range if provided
        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }
        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

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
                // Default sorting
                $query->orderBy('id', 'desc');
                break;
        }

        $perPage = $request->input('per_page', 20);
        $products = $query->paginate($perPage);
        // Map each product to array with merged images and categories
        $productsData = [];
        foreach ($products->items() as $product) {
            $data = $product->toArray();
            $images = [];
            if (!empty($product->main_image)) {
                $images[] = url('storage/' . ltrim($product->main_image, '/'));
            }
            if (!empty($product->images)) {
                foreach ($product->images as $img) {
                    $imgUrl = url('storage/' . ltrim($img->image_path, '/'));
                    if (!in_array($imgUrl, $images)) {
                        $images[] = $imgUrl;
                    }
                }
            }
            $data['images'] = $images;
            // Add categories info
            $data['categories'] = $product->categories->map(function($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                ];
            });
            $productsData[] = $data;
        }
        // Build pagination meta manually
        $productsArray = [
            'current_page' => $products->currentPage(),
            'data' => $productsData,
            'first_page_url' => $products->url(1),
            'from' => $products->firstItem(),
            'last_page' => $products->lastPage(),
            'last_page_url' => $products->url($products->lastPage()),
            'next_page_url' => $products->nextPageUrl(),
            'path' => $products->path(),
            'per_page' => $products->perPage(),
            'prev_page_url' => $products->previousPageUrl(),
            'to' => $products->lastItem(),
            'total' => $products->total(),
        ];
        return response()->json([
            'success' => true,
            'products' => $productsArray
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_ids' => 'required|array',
            'category_ids.*' => 'integer|exists:categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'gallery_images.*' => 'nullable|file|image',
        ]);
        // Remove category_id from fillable if present
        $productData = $validated;
        unset($productData['category_ids']);
        $product = Product::create($productData);

        // Attach categories
        $product->categories()->sync($validated['category_ids']);

        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $galleryImage) {
                $path = $galleryImage->store('products', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Product added.', 'product' => $product->load('categories')]);
    }

    // Update product (admin)
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'price' => 'numeric',
            'stock' => 'integer',
            'category_ids' => 'array',
            'category_ids.*' => 'integer|exists:categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
        ]);
        $productData = $validated;
        unset($productData['category_ids']);
        $product->update($productData);
        // Sync categories if provided
        if (isset($validated['category_ids'])) {
            $product->categories()->sync($validated['category_ids']);
        }
        return response()->json(['success' => true, 'message' => 'Product updated.', 'product' => $product->load('categories')]);
    }

    // Delete product (admin)
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['success' => true, 'message' => 'Product deleted.']);
    }

    // Get related products based on cart items
    public function getRelatedByCart(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer',
            'limit' => 'nullable|integer|min:1|max:20'
        ]);

        $productIds = $validated['product_ids'];
        $limit = $validated['limit'] ?? 8;

        // Get cart products to find their categories and brands
        $cartProducts = Product::with(['categories', 'brand'])
            ->whereIn('id', $productIds)
            ->get();

        if ($cartProducts->isEmpty()) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }

        // Collect category IDs and brand IDs from cart items
        $categoryIds = [];
        $brandIds = [];

        foreach ($cartProducts as $product) {
            if ($product->brand_id) {
                $brandIds[] = $product->brand_id;
            }
            foreach ($product->categories as $category) {
                $categoryIds[] = $category->id;
            }
        }

        $categoryIds = array_unique($categoryIds);
        $brandIds = array_unique($brandIds);

        // Find related products
        $query = Product::with(['brand', 'images'])
            ->where('status', 'active')
            ->whereNotIn('id', $productIds); // Exclude cart items

        // Prioritize: same brand OR same category
        $query->where(function($q) use ($brandIds, $categoryIds) {
            if (!empty($brandIds)) {
                $q->whereIn('brand_id', $brandIds);
            }
            if (!empty($categoryIds)) {
                $q->orWhereHas('categories', function($catQuery) use ($categoryIds) {
                    $catQuery->whereIn('categories.id', $categoryIds);
                });
            }
        });

        $relatedProducts = $query
            ->inRandomOrder()
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $relatedProducts
        ]);
    }

    // Get frequently bought together products
    public function getFrequentlyBoughtTogether(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer',
            'limit' => 'nullable|integer|min:1|max:20'
        ]);

        $productIds = $validated['product_ids'];
        $limit = $validated['limit'] ?? 6;

        // Find orders that contain any of the cart products
        $orderIds = \DB::table('order_items')
            ->whereIn('product_id', $productIds)
            ->distinct()
            ->limit(50) // Limit orders to check for performance
            ->pluck('order_id');

        if ($orderIds->isEmpty()) {
            // No order history, fallback to related by category/brand
            return $this->getRelatedByCart($request);
        }

        // Find products that were bought in those orders
        $frequentProducts = \DB::table('order_items')
            ->select('product_id', \DB::raw('COUNT(*) as frequency'))
            ->whereIn('order_id', $orderIds)
            ->whereNotIn('product_id', $productIds) // Exclude cart items
            ->groupBy('product_id')
            ->orderByDesc('frequency')
            ->limit($limit * 2) // Get more for filtering
            ->pluck('product_id');

        if ($frequentProducts->isEmpty()) {
            // No frequently bought products, fallback
            return $this->getRelatedByCart($request);
        }

        // Get full product details with minimal relations
        $products = Product::select('id', 'name', 'slug', 'price', 'sale_price', 'main_image', 'brand_id', 'status')
            ->with(['brand:id,name'])
            ->where('status', 'active')
            ->whereIn('id', $frequentProducts)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products,
            'type' => 'frequently_bought_together'
        ]);
    }
}
