<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'cost_price',
        'stock_quantity',
        'stock_in',
        'sku',
        'type',
        'status',
        'featured',
        'barcode',
        'manage_stock',
        'external_url',
        'meta_title',
        'meta_description',
        'main_image',
        'category_id',
        'brand_id',
        'min_order_qty',
        'max_order_qty',
        'sale_start_date',
        'sale_end_date',
    ];
      public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
        // Stock movements relationship
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Calculate current stock from movements
    public function getCurrentStockAttribute()
    {
        $movements = $this->stockMovements();
        $count = $movements->count();
        if ($count > 0) {
            return $movements->sum('quantity');
        }
        return $this->stock_quantity ?? 0;
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function downloads()
    {
        return $this->hasMany(ProductDownload::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute_values');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }

    // Add orders relationship (many-to-many)
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items', 'product_id', 'order_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug) && !empty($model->name)) {
                $model->slug = Str::slug($model->name);
            }
        });
        static::updating(function ($model) {
            if (empty($model->slug) && !empty($model->name)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }
}
