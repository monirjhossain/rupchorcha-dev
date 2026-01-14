<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'order_date',
        'status',
        'notes',
        'total',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
       public function created_by()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updated_by()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    public function approved_by()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    public function received_by()
    {
        return $this->belongsTo(\App\Models\User::class, 'received_by');
    }

    public function cancelled_by()
    {
        return $this->belongsTo(\App\Models\User::class, 'cancelled_by');
    }
        public function status_history()
    {
        return $this->hasMany(\App\Models\PurchaseOrderStatusHistory::class, 'purchase_order_id');
    }
}
