<?php
namespace App\Http\Controllers;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller {
    // AJAX coupon validation for order edit page
    public function validateAjax(Request $request) {
        $code = $request->input('code');
        // Always use subtotal (product sum) for coupon calculation
        $subtotal = floatval($request->input('subtotal', 0));
        $coupon = Coupon::where('code', $code)->where('active', true)
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })->first();
        if (!$coupon) {
            return response()->json(['valid' => false, 'discount' => 0]);
        }
        // Check min order amount (on subtotal)
        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            return response()->json(['valid' => false, 'discount' => 0]);
        }
        // Calculate discount (always on subtotal)
        $discount = 0;
        if ($coupon->type === 'fixed') {
            $discount = floatval($coupon->value);
        } elseif ($coupon->type === 'percent') {
            $discount = ($coupon->value / 100.0) * $subtotal;
            if ($coupon->max_discount && $discount > $coupon->max_discount) {
                $discount = $coupon->max_discount;
            }
        }
        // Don't allow discount more than subtotal
        if ($discount > $subtotal) $discount = $subtotal;
        return response()->json(['valid' => true, 'discount' => round($discount, 2)]);
    }
    public function index() {
        $coupons = Coupon::orderByDesc('id')->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }
    public function create() {
        return view('admin.coupons.create');
    }
    public function store(Request $request) {
        $data = $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'type' => 'required|string',
            'value' => 'nullable|numeric',
            'max_discount' => 'nullable|numeric',
            'min_order_amount' => 'nullable|numeric',
            'usage_limit' => 'nullable|integer',
            'usage_limit_per_user' => 'nullable|integer',
            'start_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
            'active' => 'boolean',
            'product_ids' => 'array',
            'category_ids' => 'array',
            'brand_ids' => 'array',
            'user_ids' => 'array',
            'first_time_customer_only' => 'boolean',
            'exclude_sale_items' => 'boolean',
        ]);
        Coupon::create($data);
        return redirect()->route('coupons.index')->with('success', 'Coupon created successfully.');
    }
    public function edit(Coupon $coupon) {
        return view('admin.coupons.edit', compact('coupon'));
    }
    public function update(Request $request, Coupon $coupon) {
        $data = $request->validate([
            'code' => 'required|string|unique:coupons,code,' . $coupon->id,
            'type' => 'required|string',
            'value' => 'nullable|numeric',
            'max_discount' => 'nullable|numeric',
            'min_order_amount' => 'nullable|numeric',
            'usage_limit' => 'nullable|integer',
            'usage_limit_per_user' => 'nullable|integer',
            'start_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
            'active' => 'boolean',
            'product_ids' => 'array',
            'category_ids' => 'array',
            'brand_ids' => 'array',
            'user_ids' => 'array',
            'first_time_customer_only' => 'boolean',
            'exclude_sale_items' => 'boolean',
        ]);
        $coupon->update($data);
        return redirect()->route('coupons.index')->with('success', 'Coupon updated successfully.');
    }
    public function destroy(Coupon $coupon) {
        $coupon->delete();
        return redirect()->route('coupons.index')->with('success', 'Coupon deleted successfully.');
    }
}
