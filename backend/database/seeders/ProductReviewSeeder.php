<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductReview;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Str;

class ProductReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $users = User::all();
        foreach ($products as $product) {
            $userCount = min(3, $users->count());
            foreach ($users->random($userCount) as $user) {
                ProductReview::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'order_id' => null,
                    'rating' => rand(1, 5),
                    'comment' => Str::random(30),
                    'images' => [],
                    'status' => ['pending', 'approved', 'rejected'][rand(0,2)],
                ]);
            }
        }
    }
}
