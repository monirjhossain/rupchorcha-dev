<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function shippingZones()
    {
        return $this->belongsToMany(ShippingZone::class, 'shipping_method_zone');
    }

    public function conditions()
    {
        return $this->hasMany(ShippingMethodCondition::class);
    }
}
