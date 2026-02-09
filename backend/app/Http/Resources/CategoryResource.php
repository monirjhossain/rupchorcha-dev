<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'banner_image' => $this->banner_image ? url('storage/' . $this->banner_image) : null,
            // Infinite recursion protection: only show products if explicitly loaded and we are not inside a product already
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'products_count' => $this->whenCounted('products'),
        ];
    }
}
