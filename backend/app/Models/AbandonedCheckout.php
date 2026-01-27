<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbandonedCheckout extends Model
{
    protected $fillable = [
        'user_id', 'email', 'cart_data', 'started_at', 'last_activity_at', 'recovered_at', 'status'
    ];

    protected $casts = [
        'cart_data' => 'array',
        'started_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'recovered_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
