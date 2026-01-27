<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Get current user's or guest's cart
    public function index(Request $request)
    {
        $cart = $this->getCart($request);
        $items = $cart ? $cart->items()->with('product')->get() : collect();
        return response()->json([
            'success' => true,
            'cart' => $cart,
            'items' => $items
        ]);
    }

    // Add or update item in cart
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $cart = $this->getCart($request, true);
        $item = $cart->items()->where('product_id', $request->product_id)->first();
        if ($item) {
            $item->quantity += $request->quantity;
            $item->save();
        } else {
            $cart->items()->create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }
        $this->saveAbandonedCheckout($request, $cart);
        return $this->index($request);
    }

    // Update item quantity
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $cart = $this->getCart($request);
        $item = $cart ? $cart->items()->where('product_id', $request->product_id)->first() : null;
        if ($item) {
            $item->quantity = $request->quantity;
            $item->save();
        }
        $this->saveAbandonedCheckout($request, $cart);
        return $this->index($request);
    }

    // Remove item from cart
    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        $cart = $this->getCart($request);
        if ($cart) {
            $cart->items()->where('product_id', $request->product_id)->delete();
        }
        $this->saveAbandonedCheckout($request, $cart);
        return $this->index($request);
    }

    // Save or update abandoned checkout record
    private function saveAbandonedCheckout(Request $request, $cart)
    {
        if (!$cart) return;
        $cartItems = $cart->items()->with('product')->get();
        if ($cartItems->isEmpty()) {
            // Optionally: delete abandoned record if cart is empty
            if ($cart->user_id) {
                \App\Models\AbandonedCheckout::where('user_id', $cart->user_id)
                    ->where('status', 'abandoned')
                    ->delete();
            } else if ($cart->session_id) {
                \App\Models\AbandonedCheckout::where('cart_data->cart_id', $cart->id)
                    ->where('status', 'abandoned')
                    ->delete();
            }
            return;
        }
        $userId = $cart->user_id;
        $email = $userId ? optional($cart->user)->email : $request->input('email');
        $cartData = [
            'cart_id' => $cart->id,
            'session_id' => $cart->session_id ?? null,
            'items' => $cartItems->map(function($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name ?? '',
                    'quantity' => $item->quantity,
                ];
            }),
        ];
        $now = now();
        if ($userId) {
            // Save for logged-in user
            $abandoned = \App\Models\AbandonedCheckout::updateOrCreate(
                [
                    'user_id' => $userId,
                    'status' => 'abandoned',
                ],
                [
                    'email' => $email,
                    'cart_data' => $cartData,
                    'started_at' => $cart->created_at ?? $now,
                    'last_activity_at' => $now,
                ]
            );
        } else if ($email) {
            // Save for guest with email
            $abandoned = \App\Models\AbandonedCheckout::updateOrCreate(
                [
                    'email' => $email,
                    'cart_data->cart_id' => $cart->id,
                    'status' => 'abandoned',
                ],
                [
                    'cart_data' => $cartData,
                    'started_at' => $cart->created_at ?? $now,
                    'last_activity_at' => $now,
                ]
            );
        }
    }

    // Helper: get or create cart for user/session
    private function getCart(Request $request, $create = false)
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        } else {
            // For API routes, use a unique identifier from the request (IP + User-Agent)
            // Fixed: Don't use session() in API routes - use IP + User-Agent instead
            $guestId = hash('sha256', $request->ip() . $request->header('user-agent', 'unknown'));
            $cart = Cart::where('session_id', $guestId)->first();
            if (!$cart && $create) {
                $cart = Cart::create(['session_id' => $guestId]);
            }
        }
        return $cart;
    }
}
