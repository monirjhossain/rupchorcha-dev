<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Add a review
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
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
        $review = Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Mirror into ProductReview so that admin dashboard (/admin/reviews)
        // can see and manage this review as well.
        try {
            ProductReview::create([
                'product_id' => $request->product_id,
                'user_id' => Auth::id(),
                'order_id' => null,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'images' => $images,
                'status' => 'pending',
            ]);
        } catch (\Throwable $e) {
            // Do not block API response if mirroring fails
        }

        return response()->json(['success' => true, 'message' => 'Review added.', 'data' => $review]);
    }

    // View reviews for a product
    public function show($product_id)
    {
        $currentUserId = Auth::id();

        $reviews = Review::where('product_id', $product_id)->with('user')->get();

        // Attach images and moderation status from mirrored ProductReview records if present
        $productReviews = ProductReview::where('product_id', $product_id)->get()->groupBy('user_id');

        $mapped = $reviews->map(function ($review) use ($productReviews) {
            $images = [];
            $status = null;
            $hasMirror = false;

            if (isset($productReviews[$review->user_id])) {
                // Pick the most recent ProductReview for this user & product
                $pr = $productReviews[$review->user_id]->sortByDesc('created_at')->first();
                if ($pr) {
                    $hasMirror = true;
                    if (is_array($pr->images)) {
                        $images = $pr->images;
                    }
                    $status = $pr->status;
                }
            }

            // If we have a mirrored ProductReview row, only explicit 'approved'
            // is treated as approved. Any other / null value means pending.
            // For legacy reviews without a mirror row, treat as approved.
            $effectiveStatus = $hasMirror ? ($status ?? 'pending') : 'approved';

            $review->images = $images;
            $review->status = $effectiveStatus;

            return $review;
        });

        // Only show approved reviews to others; always show own review to the author
        $filtered = $mapped->filter(function ($review) use ($currentUserId) {
            // Author always sees their own review
            if ($currentUserId && (int) $review->user_id === (int) $currentUserId) {
                return true;
            }

            // Everyone else only sees approved reviews
            return ($review->status ?? 'approved') === 'approved';
        })->values();

        return response()->json(['success' => true, 'data' => $filtered]);
    }

    // Update a review
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        if ($review->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        $request->validate([
            'rating' => 'integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);
        $review->update($request->only('rating', 'comment'));
        return response()->json(['success' => true, 'message' => 'Review updated.', 'data' => $review]);
    }

    // Delete a review
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        if ($review->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        $review->delete();
        return response()->json(['success' => true, 'message' => 'Review deleted.']);
    }

    // Get product rating summary
    public function ratingSummary($product_id)
    {
        $reviews = Review::where('product_id', $product_id)->get();

        // Apply the same approval logic for aggregate rating: only approved reviews
        $productReviews = ProductReview::where('product_id', $product_id)->get()->groupBy('user_id');

        $approved = $reviews->filter(function ($review) use ($productReviews) {
            $status = null;
            $hasMirror = false;

            if (isset($productReviews[$review->user_id])) {
                $pr = $productReviews[$review->user_id]->sortByDesc('created_at')->first();
                if ($pr) {
                    $hasMirror = true;
                    $status = $pr->status;
                }
            }

            $effectiveStatus = $hasMirror ? ($status ?? 'pending') : 'approved';

            return $effectiveStatus === 'approved';
        });

        $average = $approved->avg('rating');
        $count = $approved->count();
        return response()->json(['success' => true, 'average_rating' => $average, 'total_reviews' => $count]);
    }
}
