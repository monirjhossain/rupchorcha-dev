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
        return $this->index($request);
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
