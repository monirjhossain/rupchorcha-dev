<?php
namespace App\Http\Controllers\API;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    // Add a review
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);
        $review = Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);
        return response()->json(['success' => true, 'message' => 'Review added.', 'review' => $review]);
    }

    // View reviews for a product
    public function show($product_id)
    {
        $reviews = Review::where('product_id', $product_id)->with('user')->get();
        return response()->json(['success' => true, 'reviews' => $reviews]);
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
        return response()->json(['success' => true, 'message' => 'Review updated.', 'review' => $review]);
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
        $average = $reviews->avg('rating');
        $count = $reviews->count();
        return response()->json(['success' => true, 'average_rating' => $average, 'total_reviews' => $count]);
    }
}
