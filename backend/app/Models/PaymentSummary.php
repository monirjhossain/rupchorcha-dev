<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSummary extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'user_id',
        'amount',
        'payment_method',
        'status',
        'transaction_id',
        'paid_at',
    ];
    protected $casts = [
        'paid_at' => 'datetime',
    ];
}
