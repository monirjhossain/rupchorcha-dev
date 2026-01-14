<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Discount extends Model {
    protected $fillable = [
        'title', 'type', 'product_ids', 'combo_product_ids', 'category_ids', 'brand_ids', 'discount_value', 'discount_type', 'min_quantity', 'start_at', 'expires_at', 'active'
    ];
    protected $casts = [
        'product_ids' => 'array',
        'combo_product_ids' => 'array',
        'category_ids' => 'array',
        'brand_ids' => 'array',
        'active' => 'boolean',
        'start_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
}
