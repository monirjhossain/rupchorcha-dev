<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
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
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'min_order_qty' => 'nullable|integer',
            'max_order_qty' => 'nullable|integer',
            'sale_start_date' => 'nullable|date',
            'sale_end_date' => 'nullable|date',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ];
    }
}
