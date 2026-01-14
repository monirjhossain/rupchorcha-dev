<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Arr;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $products = Product::inRandomOrder()->take(5)->get();
        $users = User::inRandomOrder()->take(5)->get();
        foreach (range(1, 5) as $i) {
            $user = $users->random();
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'completed',
                'total' => 0,
                'payment_status' => 'paid',
                'payment_method' => 'cash',
            ]);
            $orderTotal = 0;
            $orderProducts = $products->random(rand(1, 3));
            foreach ($orderProducts as $product) {
                $qty = rand(1, 5);
                $itemTotal = $product->price * $qty;
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $qty,
                ]);
                $orderTotal += $itemTotal;
            }
            $order->update(['total' => $orderTotal]);
        }
    }
}
