<?php
namespace App\Http\Controllers\API;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    // Show tag details and products by tag slug
    public function showBySlug(Request $request, $slug)
    {
        $tag = Tag::where('slug', $slug)->first();
        if (!$tag) {
            return response()->json(['error' => 'Tag not found'], 404);
        }
        
        $query = $tag->products()->with(['brand', 'categories', 'images']);
        
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
        $products->getCollection()->transform(function($product) {
            $data = $product->toArray();
            $data['images'] = [];
            if ($product->main_image) {
                $data['images'][] = url('storage/' . ltrim($product->main_image, '/'));
            }
            if ($product->images && count($product->images) > 0) {
                foreach ($product->images as $img) {
                    $imgUrl = url('storage/' . ltrim($img->image_path, '/'));
                    if (!in_array($imgUrl, $data['images'])) {
                        $data['images'][] = $imgUrl;
                    }
                }
            }
            $data['categories'] = $product->categories->map(function($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                ];
            });
            $data['brand'] = $product->brand ? [
                'id' => $product->brand->id,
                'name' => $product->brand->name,
                'slug' => $product->brand->slug,
            ] : null;
            return $data;
        });
        return response()->json([
            'success' => true,
            'tag' => [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
            ],
            'products' => $products,
        ]);
    }
}