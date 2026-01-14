<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Show bulk assign courier form.
     */
    public function bulkAssignCourierForm()
    {
        $orders = \App\Models\Order::whereNull('courier_id')->with('user')->get();
        $couriers = \App\Models\Courier::all();
        return view('admin.orders.bulk_assign', compact('orders', 'couriers'));
    }

    /**
     * Handle bulk courier assignment.
     */
    public function bulkAssignCourier(Request $request)
    {
        $request->validate([
            'courier_id' => 'required|exists:couriers,id',
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
        ]);
        \App\Models\Order::whereIn('id', $request->order_ids)->update(['courier_id' => $request->courier_id]);
        return redirect()->route('orders.index')->with('success', 'Courier assigned to selected orders.');
    }
    /**
     * Display a listing of the orders.
     */
    public function index()
    {
        $query = Order::with(['user', 'shippingAddress', 'billingAddress']);

        // Search by order ID or user name/email
        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->where('id', $search)
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%$search%")
                         ->orWhere('email', 'like', "%$search%");
                  });
            });
        }

        // Filter by status
        if ($status = request('status')) {
            $query->where('status', $status);
        }

        // Filter by payment status
        if ($payment = request('payment_status')) {
            $query->where('payment_status', $payment);
        }

        $orders = $query->latest()->paginate(20)->appends(request()->query());

        // Payment method summary
        $paymentSummary = [
            'Bkash' => Order::where('payment_method', 'Bkash')->sum('total'),
            'Cash' => Order::where('payment_method', 'Cash')->sum('total'),
            'Nagad' => Order::where('payment_method', 'Nagad')->sum('total'),
            'Bank' => Order::where('payment_method', 'Bank')->sum('total'),
            'Master Card' => Order::where('payment_method', 'Master Card')->sum('total'),
        ];

        return view('admin.orders.index', compact('orders', 'paymentSummary'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        // You may want to pass users, addresses, products, etc. for selection
        return view('admin.orders.create');
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'shipping_address_id' => 'nullable|exists:addresses,id',
            'billing_address_id' => 'nullable|exists:addresses,id',
            'status' => 'required|string',
            'total' => 'required|numeric',
            'payment_status' => 'required|string',
            'coupon_code' => 'nullable|string',
            'guest_name' => 'nullable|string',
            'guest_email' => 'nullable|email',
            'guest_phone' => 'nullable|string',
            'guest_address' => 'nullable|string',
        ]);

        // If no user_id, create a guest user
        if (empty($validated['user_id'])) {
            $user = \App\Models\User::create([
                'name' => $validated['guest_name'] ?? 'Guest',
                'email' => $validated['guest_email'] ?? 'guest_' . uniqid() . '@example.com',
                'phone' => $validated['guest_phone'] ?? null,
                'address' => $validated['guest_address'] ?? null,
                'role' => 'customer',
                'password' => bcrypt(Str::random(10)),
                'active' => true,
            ]);
            $validated['user_id'] = $user->id;
        }
        $order = Order::create($validated);

        // Store order items
        $products = $request->input('products', []);
        $prices = $request->input('prices', []);
        $quantities = $request->input('quantities', []);
        foreach ($products as $idx => $productId) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'product_name' => \App\Models\Product::find($productId)?->name ?? '',
                'price' => $prices[$idx] ?? 0,
                'quantity' => $quantities[$idx] ?? 1,
            ]);
        }

        // Coupon validation logic
        if ($request->filled('coupon_code')) {
            $coupon = \App\Models\Coupon::where('code', $request->coupon_code)->where('active', true)->first();
            if ($coupon) {
                $applies = false;
                $orderProducts = $order->items()->with('product')->get();
                foreach ($orderProducts as $item) {
                    $product = $item->product;
                    if (
                        (is_array($coupon->product_ids) && in_array($product->id, $coupon->product_ids)) ||
                        (is_array($coupon->category_ids) && in_array($product->category_id, $coupon->category_ids)) ||
                        (is_array($coupon->brand_ids) && in_array($product->brand_id, $coupon->brand_ids))
                    ) {
                        $applies = true;
                        break;
                    }
                }
                if ($applies) {
                    // Apply coupon discount logic here
                    // Example: $order->discount = $coupon->value; $order->save();
                } else {
                    // Coupon does not apply to any product/category/brand in order
                    // Optionally: $order->discount = 0; $order->save();
                }
            }
        }

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified order details.
     */
    public function show(Order $order)
    {
        $order->load(['items.product', 'user', 'shippingAddress', 'billingAddress', 'payments', 'statusHistories.changedBy']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        // You may want to pass users, addresses, products, etc. for selection
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
            $validated = $request->validate([
                'status' => 'required|string',
                'payment_status' => 'required|string',
                'payment_method' => 'nullable|string',
                'courier_id' => 'nullable|exists:couriers,id',
            ]);

            // If send_to_courier is set, assign a courier (first available if not set)
            if ($request->has('send_to_courier')) {
                $courier = $order->courier_id ? $order->courier_id : \App\Models\Courier::first()?->id;
                if ($courier) {
                    $order->courier_id = $courier;
                }
            } elseif ($request->filled('courier_id')) {
                $order->courier_id = $request->courier_id;
            }

            $order->status = $validated['status'];
            $order->payment_status = $validated['payment_status'];
            $order->payment_method = $validated['payment_method'] ?? $order->payment_method;
            $order->save();
            return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}
