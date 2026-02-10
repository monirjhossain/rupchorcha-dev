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
        'warehouse_id',
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
    // Many-to-many: A product can belong to multiple categories
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
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

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get approved reviews for this product, applying the same
     * approval logic used in the API ratingSummary endpoint.
     */
    protected function getApprovedReviews()
    {
        $reviews = Review::where('product_id', $this->id)->get();

        if ($reviews->isEmpty()) {
            return collect();
        }

        $productReviews = ProductReview::where('product_id', $this->id)
            ->get()
            ->groupBy('user_id');

        return $reviews->filter(function ($review) use ($productReviews) {
            $status = null;
            $hasMirror = false;

            if (isset($productReviews[$review->user_id])) {
                $pr = $productReviews[$review->user_id]->sortByDesc('created_at')->first();
                if ($pr) {
                    $hasMirror = true;
                    $status = $pr->status;
                }
            }

            $effectiveStatus = $hasMirror ? ($status ?? 'pending') : 'approved';

            return $effectiveStatus === 'approved';
        });
    }

    public function getAverageRatingAttribute()
    {
        $approved = $this->getApprovedReviews();

        if ($approved->count() === 0) {
            return 0;
        }

        return round($approved->avg('rating'), 2);
    }

    public function getTotalReviewsAttribute()
    {
        return $this->getApprovedReviews()->count();
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
