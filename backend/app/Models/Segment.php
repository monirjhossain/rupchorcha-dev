<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'rules',
    ];

    protected $casts = [
        'rules' => 'array',
    ];

    // Dynamic user query based on rules
    public function usersQuery()
    {
        $query = \App\Models\User::query();
        $rules = $this->rules;
        if (!$rules) return $query;
        // Example rules: min_orders, last_purchase_days, min_spent, location
        if (!empty($rules['min_orders'])) {
            $query->where('orders_count', '>=', $rules['min_orders']);
        }
        if (!empty($rules['last_purchase_days'])) {
            $query->where('last_purchase_at', '>=', now()->subDays($rules['last_purchase_days']));
        }
        if (!empty($rules['min_spent'])) {
            $query->where('total_spent', '>=', $rules['min_spent']);
        }
        if (!empty($rules['location'])) {
            $query->where('location', $rules['location']);
        }
        return $query;
    }
}
