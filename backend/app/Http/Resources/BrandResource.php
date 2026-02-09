<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'logo' => $this->image ? url('storage/' . $this->image) : null,
            'banner_image' => $this->banner_image ? url('storage/' . $this->banner_image) : null,
            'products_count' => $this->whenCounted('products'),
        ];
    }
}
