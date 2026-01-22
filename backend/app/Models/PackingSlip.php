<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackingSlip extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'slip_number',
        'generated_at',
        'printed_at',
        'meta',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'printed_at' => 'datetime',
        'meta' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function generateSlipNumber()
    {
        $year = date('y');
        $month = date('m');
        $latestSlip = self::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->orderBy('id', 'desc')
            ->first();
        
        $number = $latestSlip ? intval(substr($latestSlip->slip_number, -4)) + 1 : 1;
        return 'PKG-' . $year . $month . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function markAsPrinted()
    {
        $this->update(['printed_at' => now()]);
        return $this;
    }

    public static function generateFromOrder(Order $order)
    {
        $slip = new self();
        $slip->order_id = $order->id;
        $slip->slip_number = $slip->generateSlipNumber();
        $slip->generated_at = now();
        
        // Store shipping and item details
        $slip->meta = [
            'customer_name' => $order->user?->name ?? $order->customer_name ?? 'N/A',
            'customer_email' => $order->user?->email ?? $order->customer_email ?? 'N/A',
            'customer_phone' => $order->customer_phone ?? 'N/A',
            'shipping_address' => $order->shipping_address ?? 'N/A',
            'notes' => $order->customer_notes ?? '',
            'item_count' => $order->items->count(),
            'total_quantity' => $order->items->sum('quantity'),
        ];
        
        $slip->save();
        return $slip;
    }
}
