<?php
namespace App\Http\Controllers;
use App\Models\Coupon;
use Illuminate\Http\Request;
class CouponController extends Controller {
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
