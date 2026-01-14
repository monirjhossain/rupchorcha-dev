<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCondition extends Model
{
    use HasFactory;
    protected $fillable = [
        'type', 'target_id', 'discount_type', 'discount_value', 'free_shipping', 'notes'
    ];

}
