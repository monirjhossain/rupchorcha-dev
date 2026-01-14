<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Coupon extends Model {
    protected $fillable = [
        'code', 'type', 'value', 'max_discount', 'min_order_amount', 'usage_limit', 'usage_limit_per_user', 'start_at', 'expires_at', 'active', 'product_ids', 'category_ids', 'brand_ids', 'user_ids', 'first_time_customer_only', 'exclude_sale_items'
    ];
    protected $casts = [
        'product_ids' => 'array',
        'category_ids' => 'array',
        'brand_ids' => 'array',
        'user_ids' => 'array',
        'active' => 'boolean',
        'first_time_customer_only' => 'boolean',
        'exclude_sale_items' => 'boolean',
        'start_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
}
