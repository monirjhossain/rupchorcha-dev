<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Image URL processing logic
        $images = [];
        if ($this->main_image) {
            $images[] = url('storage/' . ltrim($this->main_image, '/'));
        }
        if ($this->images && count($this->images) > 0) {
            foreach ($this->images as $img) {
                $imgUrl = url('storage/' . ltrim($img->image_path, '/'));
                if (!in_array($imgUrl, $images)) {
                    $images[] = $imgUrl;
                }
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'price' => (float)$this->price,
            'sale_price' => (float)$this->sale_price,
            'discount_price' => (float)$this->sale_price, // Legacy support
            'stock_quantity' => $this->stock_quantity,
            'stock_status' => $this->stock_quantity > 0 ? 'in_stock' : 'out_of_stock',
            'main_image' => $this->main_image ? url('storage/' . ltrim($this->main_image, '/')) : null,
            'images' => $images,
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
