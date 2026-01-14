<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkSms extends Model
{
    use HasFactory;
    protected $fillable = [
        'message',
        'recipients',
        'status',
    ];
    protected $casts = [
        'recipients' => 'array',
    ];
}
