<?php
namespace App\Http\Controllers\API;

use App\Models\Order;
use App\Models\Product;
use App\Mail\OrderConfirmationMail;
use App\Services\ShippingCalculator;
use App\Services\CouponService;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

    // Place order (user or guest)
    public function store(StoreOrderRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = Auth::user();
            
            // Calculate shipping cost server-side to prevent tampering
            $shippingCost = ShippingCalculator::getShippingCost($validated['city'], $validated['area']);
            $shippingMethod = ShippingCalculator::getShippingMethod($validated['city']);
            
            // Calculate order total: sum of all items
            $itemsTotal = 0;
            $productIds = [];
            foreach ($validated['items'] as $item) {
                $itemsTotal += $item['price'] * $item['quantity'];
                $productIds[] = $item['product_id'];
            }
            
            // Validate and recalculate coupon discount server-side
            $discountAmount = 0;
            if (!empty($validated['coupon_code'])) {
                $couponResult = CouponService::validateCoupon(
                    $validated['coupon_code'],
                    $itemsTotal,
                    $user?->id,
                    $productIds
                );
                
                if ($couponResult['valid']) {
                    $discountAmount = $couponResult['discount'];
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => $couponResult['message']
                    ], 422);
                }
            }
            
            $total = $itemsTotal + $shippingCost - $discountAmount;
            
            $order = Order::create([
                'user_id' => $user ? $user->id : null,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'city' => $validated['city'],
                'area' => $validated['area'],
                'notes' => $validated['notes'] ?? null,
                'payment_method' => $validated['payment_method'],
                'shipping_method' => $shippingMethod,
                'shipping_cost' => $shippingCost,
                'coupon_code' => $validated['coupon_code'] ?? null,
                'discount_amount' => $discountAmount,
                'total' => $total,
                'payment_status' => 'unpaid',
                'status' => 'pending',
            ]);

            // Create order items
            $productCache = [];
            foreach ($validated['items'] as $item) {
                $productId = $item['product_id'];
                if (!isset($productCache[$productId])) {
                    $productCache[$productId] = Product::find($productId);
                }
                $product = $productCache[$productId];
                $productName = $product?->name ?? 'Unknown Product';

                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'product_name' => $productName,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'variant' => $item['variant'] ?? null,
                ]);
            }

            $order->load('items');

            // Send order confirmation email
            try {
                Mail::to($order->customer_email)
                    ->queue(new OrderConfirmationMail($order));
            } catch (\Exception $e) {
                Log::error('Failed to send order confirmation email: ' . $e->getMessage());
                // Don't fail the order creation if email fails
            }

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully.',
                'order' => [
                    'id' => $order->id,
                    'customer_name' => $order->customer_name,
                    'city' => $order->city,
                    'area' => $order->area,
                    'shipping_cost' => $order->shipping_cost,
                    'status' => $order->status,
                    'items' => $order->items,
                ]
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Order validation error: ', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed. Please check all required fields.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Order creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage()
            ], 500);
        }
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
