<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductReviewController extends Controller
{

    // Show edit form (admin)
    public function edit(ProductReview $review)
    {
        return view('admin.reviews.edit', compact('review'));
    }

    // Update review (admin)
    public function update(Request $request, ProductReview $review)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
        ]);
        $review->update($data);
        return redirect()->route('reviews.index')->with('success', 'Review updated successfully.');
    }
    // List all reviews (admin)
    public function index(Request $request)
    {
        $query = ProductReview::with(['product', 'user', 'order']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        $reviews = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->except('page'));
        $statuses = ['pending', 'approved', 'rejected'];
        $products = \App\Models\Product::all();
        return view('admin.reviews.index', compact('reviews', 'statuses', 'products'));
    }

    // Store new review (customer)
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'order_id' => 'nullable|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'images.*' => 'image|max:2048',
        ]);
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $images[] = $img->store('reviews', 'public');
            }
        }
        $review = ProductReview::create([
            'product_id' => $data['product_id'],
            'user_id' => $data['user_id'],
            'order_id' => $data['order_id'] ?? null,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
            'images' => $images,
            'status' => 'pending',
        ]);
        return response()->json(['success' => true, 'review' => $review]);
    }

    // Approve/reject review (admin)
    public function updateStatus(Request $request, ProductReview $review)
    {
        $request->validate(['status' => 'required|in:pending,approved,rejected']);
        $review->status = $request->status;
        $review->save();
        return redirect()->route('reviews.index')->with('success', 'Review status updated successfully.');
    }

    // Delete review (admin)
    public function destroy(ProductReview $review)
    {
        // Delete images
        if ($review->images) {
            foreach ($review->images as $img) {
                Storage::disk('public')->delete($img);
            }
        }
        $review->delete();
        return redirect()->route('reviews.index')->with('success', 'Review deleted successfully.');
    }
}

