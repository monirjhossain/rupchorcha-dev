<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // List all wishlist products for the authenticated user
    public function index(Request $request)
    {
        $user = $request->user();
        $wishlists = Wishlist::with('product')
            ->where('user_id', $user->id)
            ->latest()
            ->get();
        return response()->json([
            'wishlists' => $wishlists
        ]);
    }

    // Add a product to the wishlist
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        $user = $request->user();
        $wishlist = Wishlist::firstOrCreate([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
        ]);
        return response()->json([
            'message' => 'Added to wishlist',
            'wishlist' => $wishlist
        ], 201);
    }

    // Remove a product from the wishlist
    public function destroy(Request $request, $productId)
    {
        $user = $request->user();
        $deleted = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->delete();
        return response()->json([
            'message' => $deleted ? 'Removed from wishlist' : 'Not found',
        ]);
    }
}
