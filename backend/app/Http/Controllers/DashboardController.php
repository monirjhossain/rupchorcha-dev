<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSales = Order::where('status', 'completed')->sum('total');
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $topProduct = Product::withCount(['orders' => function($q) {
            $q->where('status', 'completed');
        }])->orderByDesc('orders_count')->first();
        return view('admin.dashboard', compact('totalSales', 'totalOrders', 'totalCustomers', 'topProduct'));
    }
}
