<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'api_key', 'contact_number', 'email', 'tracking_url', 'status', 'logo', 'service_area', 'delivery_types', 'notes'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
