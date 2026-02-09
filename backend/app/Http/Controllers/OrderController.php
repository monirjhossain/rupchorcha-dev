<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;

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
        $query = Order::with(['user', 'items', 'courier']);

        // Search by order ID or user name/email
        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                  ->orWhere('tracking_number', 'like', "%$search%")
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

        // Filter by courier
        if ($courier_id = request('courier_id')) {
            $query->where('courier_id', $courier_id);
        }

        // Filter by date range
        if ($date_from = request('date_from')) {
            $query->whereDate('created_at', '>=', $date_from);
        }
        if ($date_to = request('date_to')) {
            $query->whereDate('created_at', '<=', $date_to);
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
    public function store(StoreOrderRequest $request)
    {
        // Validation is handled by StoreOrderRequest
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // If no user_id, use existing user by email or create a new guest user
            if (empty($validated['user_id'])) {
            $existingUser = \App\Models\User::where('email', $validated['customer_email'])->first();
            if ($existingUser) {
                $validated['user_id'] = $existingUser->id;
            } else {
                $user = \App\Models\User::create([
                    'name' => $validated['customer_name'] ?? 'Guest',
                    'email' => $validated['customer_email'] ?? 'guest_' . uniqid() . '@example.com',
                    'phone' => $validated['customer_phone'] ?? null,
                    'address' => $validated['shipping_address'] ?? null,
                    'role' => 'customer',
                    'password' => bcrypt(Str::random(10)),
                    'active' => true,
                ]);
                $validated['user_id'] = $user->id;
            }
        }

        $orderData = [
            'user_id' => $validated['user_id'],
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'shipping_address' => $validated['shipping_address'],
            'city' => $validated['city'],
            'area' => $validated['area'],
            'notes' => $validated['notes'] ?? null,
            'payment_method' => $validated['payment_method'],
            'shipping_method' => $validated['shipping_method'],
            'shipping_cost' => $validated['shipping_cost'],
            'status' => $validated['status'],
            'total' => $validated['total'],
            'payment_status' => $validated['payment_status'],
            'coupon_code' => $validated['coupon_code'] ?? null,
            'discount_amount' => 0,
            'grand_total' => $validated['grand_total'],
            'created_by' => auth()->id(),
        ];

        $order = Order::create($orderData);

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

        // Coupon validation logic (optional, can be expanded)
        if ($request->filled('coupon_code')) {
            $coupon = \App\Models\Coupon::where('code', $request->coupon_code)->where('active', true)->first();
            if ($coupon) {
                $applies = false;
                $orderProducts = $order->items()->with('product')->get();
                foreach ($orderProducts as $item) {
                    $product = $item->product;
                    $productCategoryIds = $product->categories ? $product->categories->pluck('id')->toArray() : [];
                    if (
                        (is_array($coupon->product_ids) && in_array($product->id, $coupon->product_ids)) ||
                        (is_array($coupon->category_ids) && count(array_intersect($productCategoryIds, $coupon->category_ids))) ||
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

        DB::commit();

        // Send order confirmation email
        try {
            Mail::to($order->customer_email)
                ->queue(new OrderConfirmationMail($order));
        } catch (\Exception $e) {
            // Log::error('Failed to send order confirmation email: ' . $e->getMessage());
            // Don't fail the order creation if email fails
        }

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->with('error', 'Failed to create order: ' . $e->getMessage());
    }
    }

    /**
     * Display the specified order details.
     */
    public function show(Order $order)
    {
        $order->load(['items.product', 'user', 'payments', 'statusHistories', 'creator']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        // You may want to pass users, addresses, products, etc. for selection
        $order->load('creator');
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // Coupon logic
            $discountAmount = 0;
        $appliedCouponCode = null;
        if ($request->filled('coupon_code')) {
            $coupon = \App\Models\Coupon::where('code', $request->coupon_code)->where('active', true)->first();
            if ($coupon) {
                // For simplicity, apply coupon to whole order (customize as needed)
                if ($coupon->type === 'fixed') {
                    $discountAmount = $coupon->value;
                } elseif ($coupon->type === 'percent') {
                    // Will apply after total is calculated
                    $appliedCouponCode = $coupon;
                }
                $appliedCouponCode = $coupon->code;
            }
        }

        // If send_to_courier is set, assign a courier (first available if not set)
        if ($request->has('send_to_courier')) {
            $courier = $order->courier_id ? $order->courier_id : \App\Models\Courier::first()?->id;
            if ($courier) {
                $order->courier_id = $courier;
            }
        } elseif ($request->filled('courier_id')) {
            $order->courier_id = $request->courier_id;
        }

        $oldStatus = $order->status;
        if ($request->filled('status')) $order->status = $validated['status'];
        if ($request->filled('payment_status')) $order->payment_status = $validated['payment_status'];
        if ($request->filled('payment_method')) $order->payment_method = $validated['payment_method'];
        if ($request->filled('tracking_number')) $order->tracking_number = $validated['tracking_number'];
        if ($request->filled('admin_note')) $order->admin_note = $validated['admin_note'];

        // Save coupon code and discount (will update grand_total after items)
        $order->coupon_code = $appliedCouponCode;
        $order->discount_amount = $discountAmount;
        $order->save();

        // Update order items (products)
        // Remove old items
        $order->items()->delete();
        $productIds = $request->input('product_id', []);
        $quantities = $request->input('quantity', []);
        $unitPrices = $request->input('unit_price', []);
        $total = 0;
        foreach ($productIds as $idx => $productId) {
            $qty = $quantities[$idx] ?? 1;
            $price = $unitPrices[$idx] ?? 0;
            $subtotal = $qty * $price;
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'product_name' => \App\Models\Product::find($productId)?->name ?? '',
                'price' => $price,
                'quantity' => $qty,
            ]);
            $total += $subtotal;
        }
        // Update order total and grand_total
        $order->total = $total;
        // Coupon/discount logic
        if ($appliedCouponCode && isset($coupon) && $coupon && $coupon->type === 'percent') {
            $discountAmount = ($coupon->value / 100) * $total;
            $order->discount_amount = $discountAmount;
        }
        $order->grand_total = $total + ($order->shipping_cost ?? 0) - $discountAmount;
        $order->save();

        // Ensure items and product relations are loaded
        $order->load('items.product');

        // Decrement stock only when status changes to 'complete'
        if ($oldStatus !== 'complete' && $order->status === 'completed') {
            foreach ($order->items as $item) {
                $product = $item->product;
                if ($product && $product->manage_stock) {
                    // Decrement stock_quantity field
                    $product->stock_quantity = max(0, ($product->stock_quantity ?? 0) - $item->quantity);
                    $product->save();
                    // Add StockMovement entry
                    \App\Models\StockMovement::create([
                        'product_id' => $product->id,
                        'type' => 'out',
                        'quantity' => -1 * $item->quantity,
                        'reason' => 'Order Completed (Order ID: ' . $order->id . ')',
                        'user_id' => auth()->id() ?? null,
                    ]);
                }
            }
        }

            DB::commit();
            
            return redirect()->route('orders.index')->with('success', 'Order updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update order: ' . $e->getMessage());
        }
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
