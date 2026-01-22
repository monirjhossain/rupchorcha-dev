<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'order_id',
        'amount',
        'discount',
        'tax',
        'total',
        'status',
        'issued_at',
        'due_at',
        'paid_at',
        'notes',
        'meta',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'issued_at' => 'datetime',
        'due_at' => 'datetime',
        'paid_at' => 'datetime',
        'meta' => 'json',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function markAsPaid()
    {
        $this->status = 'paid';
        $this->paid_at = now();
        $this->save();
    }

    public function markAsUnpaid()
    {
        $this->status = 'unpaid';
        $this->paid_at = null;
        $this->save();
    }

    public function markAsSent()
    {
        $this->status = 'sent';
        $this->save();
    }
}
