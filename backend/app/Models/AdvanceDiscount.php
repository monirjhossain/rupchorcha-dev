<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvanceDiscount extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'discount_type', // percentage, fixed, etc.
        'amount',
        'start_date',
        'end_date',
        'min_purchase',
        'max_discount',
        'status',
    ];
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}
