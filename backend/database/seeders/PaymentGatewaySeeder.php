<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentGateway;

class PaymentGatewaySeeder extends Seeder
{
    public function run()
    {
        PaymentGateway::insert([
            [
                'name' => 'Bkash',
                'slug' => 'bkash',
                'config' => json_encode(['sandbox' => true]),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nagad',
                'slug' => 'nagad',
                'config' => json_encode(['sandbox' => true]),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SSLCommerz',
                'slug' => 'sslcommerz',
                'config' => json_encode(['sandbox' => true]),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
