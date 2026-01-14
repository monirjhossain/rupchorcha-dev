<?php
namespace App\Http\Controllers\API;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    // List all orders (admin)
    public function index()
    {
        $orders = Order::with('user')->paginate(20);
        return response()->json(['success' => true, 'orders' => $orders]);
    }

    // Show order details
    public function show($id)
    {
        $order = Order::with('user')->findOrFail($id);
        return response()->json(['success' => true, 'order' => $order]);
    }

    // Place order (user)
    public function store(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|integer',
            'address' => 'required|string',
            'payment_method' => 'required|string',
            'shipping_method_id' => 'required|integer|exists:shipping_methods,id',
        ]);
        $order = Order::create([
            'user_id' => Auth::id(),
            'cart_id' => $request->cart_id,
            'address' => $request->address,
            'payment_method' => $request->payment_method,
            'shipping_method_id' => $request->shipping_method_id,
            'status' => 'pending',
        ]);
        return response()->json(['success' => true, 'message' => 'Order placed.', 'order' => $order]);
    }

    // Update order status (admin)
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate([
            'status' => 'required|string',
        ]);
        $order->status = $request->status;
        $order->save();
        return response()->json(['success' => true, 'message' => 'Order status updated.', 'order' => $order]);
    }

    // Cancel order (user)
    public function cancel($id)
    {
        $order = Order::findOrFail($id);
        if ($order->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        $order->status = 'cancelled';
        $order->save();
        return response()->json(['success' => true, 'message' => 'Order cancelled.']);
    }
}
