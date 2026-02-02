<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TagController;
use App\Http\Controllers\API\WishlistController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Tags API routes
Route::get('tags/{slug}', [TagController::class, 'showBySlug']);

// Cart API routes
Route::get('/cart', [\App\Http\Controllers\API\CartController::class, 'index']);
Route::post('/cart/add', [\App\Http\Controllers\API\CartController::class, 'add']);
Route::post('/cart/update', [\App\Http\Controllers\API\CartController::class, 'update']);
Route::post('/cart/remove', [\App\Http\Controllers\API\CartController::class, 'remove']);

// Coupon API routes
Route::post('/coupons/validate', [\App\Http\Controllers\API\CouponController::class, 'validateCoupon']);

// Shipping Options API routes (Best Practice - Dynamic)
Route::get('/shipping/zones-with-methods', [\App\Http\Controllers\API\ShippingOptionsController::class, 'getZonesWithMethods']);
Route::post('/shipping/methods-by-location', [\App\Http\Controllers\API\ShippingOptionsController::class, 'getMethodsByLocation']);
Route::get('/shipping/all-methods', [\App\Http\Controllers\API\ShippingOptionsController::class, 'getAllMethods']);

// Discount API routes (Best Practice)
Route::get('/products/slug/{slug}', [\App\Http\Controllers\API\ProductController::class, 'showBySlug']);
Route::get('/discounts', [\App\Http\Controllers\API\DiscountController::class, 'index']);
Route::get('/discounts/{id}', [\App\Http\Controllers\API\DiscountController::class, 'show']);
Route::middleware('auth:sanctum')->post('/discounts', [\App\Http\Controllers\API\DiscountController::class, 'store']);
Route::middleware('auth:sanctum')->put('/discounts/{id}', [\App\Http\Controllers\API\DiscountController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/discounts/{id}', [\App\Http\Controllers\API\DiscountController::class, 'destroy']);
Route::middleware('auth:sanctum')->get('/orders', [\App\Http\Controllers\API\OrderController::class, 'index']);
Route::get('/orders/{id}', [\App\Http\Controllers\API\OrderController::class, 'show']);
Route::post('/orders', [\App\Http\Controllers\API\OrderController::class, 'store']); // Allow guest orders (with user detection)
Route::middleware('auth:sanctum')->put('/orders/{id}/status', [\App\Http\Controllers\API\OrderController::class, 'updateStatus']);
Route::middleware('auth:sanctum')->post('/orders/{id}/cancel', [\App\Http\Controllers\API\OrderController::class, 'cancel']);
Route::get('/shipping-zones', [\App\Http\Controllers\API\ShippingZoneController::class, 'index']);
Route::get('/shipping-zones/{id}', [\App\Http\Controllers\API\ShippingZoneController::class, 'show']);
Route::middleware('auth:sanctum')->post('/shipping-zones', [\App\Http\Controllers\API\ShippingZoneController::class, 'store']);
Route::middleware('auth:sanctum')->put('/shipping-zones/{id}', [\App\Http\Controllers\API\ShippingZoneController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/shipping-zones/{id}', [\App\Http\Controllers\API\ShippingZoneController::class, 'destroy']);
Route::get('/shipping-methods', [\App\Http\Controllers\API\ShippingController::class, 'methods']);
Route::middleware('auth:sanctum')->post('/shipping-methods', [\App\Http\Controllers\API\ShippingController::class, 'store']);
Route::middleware('auth:sanctum')->put('/shipping-methods/{id}', [\App\Http\Controllers\API\ShippingController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/shipping-methods/{id}', [\App\Http\Controllers\API\ShippingController::class, 'destroy']);
Route::post('/shipping/calculate', [\App\Http\Controllers\API\ShippingController::class, 'calculate']);
Route::get('/warehouses', [\App\Http\Controllers\API\WarehouseController::class, 'index']);
// Transaction API routes (Best Practice)
Route::get('/transactions', [\App\Http\Controllers\API\TransactionController::class, 'index']);
Route::get('/transactions/{id}', [\App\Http\Controllers\API\TransactionController::class, 'show']);
Route::middleware('auth:sanctum')->post('/transactions', [\App\Http\Controllers\API\TransactionController::class, 'store']);
Route::middleware('auth:sanctum')->put('/transactions/{id}', [\App\Http\Controllers\API\TransactionController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/transactions/{id}', [\App\Http\Controllers\API\TransactionController::class, 'destroy']);
// PaymentGateway API routes (Best Practice)
Route::get('/payment-gateways', [\App\Http\Controllers\API\PaymentGatewayController::class, 'index']);
Route::get('/payment-gateways/{id}', [\App\Http\Controllers\API\PaymentGatewayController::class, 'show']);
Route::middleware('auth:sanctum')->post('/payment-gateways', [\App\Http\Controllers\API\PaymentGatewayController::class, 'store']);
Route::middleware('auth:sanctum')->put('/payment-gateways/{id}', [\App\Http\Controllers\API\PaymentGatewayController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/payment-gateways/{id}', [\App\Http\Controllers\API\PaymentGatewayController::class, 'destroy']);
// PaymentSummary API routes (Best Practice)
Route::get('/payment-summaries', [\App\Http\Controllers\API\PaymentSummaryController::class, 'index']);
Route::get('/payment-summaries/{id}', [\App\Http\Controllers\API\PaymentSummaryController::class, 'show']);
Route::middleware('auth:sanctum')->post('/payment-summaries', [\App\Http\Controllers\API\PaymentSummaryController::class, 'store']);
Route::middleware('auth:sanctum')->put('/payment-summaries/{id}', [\App\Http\Controllers\API\PaymentSummaryController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/payment-summaries/{id}', [\App\Http\Controllers\API\PaymentSummaryController::class, 'destroy']);
// AdvanceDiscount API routes (Best Practice)
Route::get('/advance-discounts', [\App\Http\Controllers\API\AdvanceDiscountController::class, 'index']);
Route::get('/advance-discounts/{id}', [\App\Http\Controllers\API\AdvanceDiscountController::class, 'show']);
Route::middleware('auth:sanctum')->post('/advance-discounts', [\App\Http\Controllers\API\AdvanceDiscountController::class, 'store']);
Route::middleware('auth:sanctum')->put('/advance-discounts/{id}', [\App\Http\Controllers\API\AdvanceDiscountController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/advance-discounts/{id}', [\App\Http\Controllers\API\AdvanceDiscountController::class, 'destroy']);
// CampaignHistory API routes (Best Practice)
Route::get('/campaign-histories', [\App\Http\Controllers\API\CampaignHistoryController::class, 'index']);
Route::get('/campaign-histories/{id}', [\App\Http\Controllers\API\CampaignHistoryController::class, 'show']);
Route::middleware('auth:sanctum')->post('/campaign-histories', [\App\Http\Controllers\API\CampaignHistoryController::class, 'store']);
Route::middleware('auth:sanctum')->put('/campaign-histories/{id}', [\App\Http\Controllers\API\CampaignHistoryController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/campaign-histories/{id}', [\App\Http\Controllers\API\CampaignHistoryController::class, 'destroy']);
// BulkEmail API routes (Best Practice)
Route::get('/bulk-emails', [\App\Http\Controllers\API\BulkEmailController::class, 'index']);
Route::get('/bulk-emails/{id}', [\App\Http\Controllers\API\BulkEmailController::class, 'show']);
Route::middleware('auth:sanctum')->post('/bulk-emails', [\App\Http\Controllers\API\BulkEmailController::class, 'store']);
Route::middleware('auth:sanctum')->put('/bulk-emails/{id}', [\App\Http\Controllers\API\BulkEmailController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/bulk-emails/{id}', [\App\Http\Controllers\API\BulkEmailController::class, 'destroy']);
// Bulk SMS API routes (Best Practice)
Route::get('/bulk-sms', [\App\Http\Controllers\API\BulkSmsController::class, 'index']);
Route::get('/bulk-sms/{id}', [\App\Http\Controllers\API\BulkSmsController::class, 'show']);
Route::middleware('auth:sanctum')->post('/bulk-sms', [\App\Http\Controllers\API\BulkSmsController::class, 'store']);
Route::middleware('auth:sanctum')->put('/bulk-sms/{id}', [\App\Http\Controllers\API\BulkSmsController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/bulk-sms/{id}', [\App\Http\Controllers\API\BulkSmsController::class, 'destroy']);
// Address API routes (Best Practice)
Route::get('/addresses', [\App\Http\Controllers\API\AddressController::class, 'index']);
Route::get('/addresses/{id}', [\App\Http\Controllers\API\AddressController::class, 'show']);
Route::middleware('auth:sanctum')->post('/addresses', [\App\Http\Controllers\API\AddressController::class, 'store']);
Route::middleware('auth:sanctum')->put('/addresses/{id}', [\App\Http\Controllers\API\AddressController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/addresses/{id}', [\App\Http\Controllers\API\AddressController::class, 'destroy']);
// Refund API routes (Best Practice)
Route::get('/refunds', [\App\Http\Controllers\API\RefundController::class, 'index']);
Route::get('/refunds/{id}', [\App\Http\Controllers\API\RefundController::class, 'show']);
Route::middleware('auth:sanctum')->post('/refunds', [\App\Http\Controllers\API\RefundController::class, 'store']);
Route::middleware('auth:sanctum')->put('/refunds/{id}', [\App\Http\Controllers\API\RefundController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/refunds/{id}', [\App\Http\Controllers\API\RefundController::class, 'destroy']);
Route::get('/warehouses/{id}', [\App\Http\Controllers\API\WarehouseController::class, 'show']);
Route::middleware('auth:sanctum')->post('/warehouses', [\App\Http\Controllers\API\WarehouseController::class, 'store']);
Route::middleware('auth:sanctum')->put('/warehouses/{id}', [\App\Http\Controllers\API\WarehouseController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/warehouses/{id}', [\App\Http\Controllers\API\WarehouseController::class, 'destroy']);
// Courier API routes (Best Practice)
Route::get('/couriers', [\App\Http\Controllers\API\CourierController::class, 'index']);
Route::get('/couriers/{id}', [\App\Http\Controllers\API\CourierController::class, 'show']);
Route::middleware('auth:sanctum')->post('/couriers', [\App\Http\Controllers\API\CourierController::class, 'store']);
Route::middleware('auth:sanctum')->put('/couriers/{id}', [\App\Http\Controllers\API\CourierController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/couriers/{id}', [\App\Http\Controllers\API\CourierController::class, 'destroy']);
Route::get('/suppliers', [\App\Http\Controllers\API\SupplierController::class, 'index']);
Route::get('/suppliers/{id}', [\App\Http\Controllers\API\SupplierController::class, 'show']);
Route::middleware('auth:sanctum')->post('/suppliers', [\App\Http\Controllers\API\SupplierController::class, 'store']);
Route::middleware('auth:sanctum')->put('/suppliers/{id}', [\App\Http\Controllers\API\SupplierController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/suppliers/{id}', [\App\Http\Controllers\API\SupplierController::class, 'destroy']);
Route::middleware('auth:sanctum')->post('/products/import', [\App\Http\Controllers\API\ProductBulkImportController::class, 'import']);
Route::get('/attributes', [\App\Http\Controllers\API\AttributeController::class, 'index']);
Route::get('/attributes/{id}', [\App\Http\Controllers\API\AttributeController::class, 'show']);
Route::middleware('auth:sanctum')->post('/attributes', [\App\Http\Controllers\API\AttributeController::class, 'store']);
Route::middleware('auth:sanctum')->put('/attributes/{id}', [\App\Http\Controllers\API\AttributeController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/attributes/{id}', [\App\Http\Controllers\API\AttributeController::class, 'destroy']);
Route::get('/brands', [\App\Http\Controllers\API\BrandController::class, 'index']);
Route::get('/brands/{id}', [\App\Http\Controllers\API\BrandController::class, 'show']);
Route::get('/brands/{slug}/products', [\App\Http\Controllers\API\BrandController::class, 'productsBySlug']);
Route::middleware('auth:sanctum')->post('/brands', [\App\Http\Controllers\API\BrandController::class, 'store']);
Route::middleware('auth:sanctum')->put('/brands/{id}', [\App\Http\Controllers\API\BrandController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/brands/{id}', [\App\Http\Controllers\API\BrandController::class, 'destroy']);
Route::get('/categories', [\App\Http\Controllers\API\CategoryController::class, 'index']);
Route::get('/categories/{id}', [\App\Http\Controllers\API\CategoryController::class, 'show']);
Route::middleware('auth:sanctum')->post('/categories', [\App\Http\Controllers\API\CategoryController::class, 'store']);
Route::middleware('auth:sanctum')->put('/categories/{id}', [\App\Http\Controllers\API\CategoryController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/categories/{id}', [\App\Http\Controllers\API\CategoryController::class, 'destroy']);
Route::get('/products/price-range', [\App\Http\Controllers\API\ProductController::class, 'priceRange']);
Route::get('/products', [\App\Http\Controllers\API\ProductController::class, 'index']);
Route::get('/products/{id}', [\App\Http\Controllers\API\ProductController::class, 'show']);
Route::post('/products/related-by-cart', [\App\Http\Controllers\API\ProductController::class, 'getRelatedByCart']);
Route::post('/products/frequently-bought-together', [\App\Http\Controllers\API\ProductController::class, 'getFrequentlyBoughtTogether']);
Route::middleware('auth:sanctum')->post('/products', [\App\Http\Controllers\API\ProductController::class, 'store']);
Route::middleware('auth:sanctum')->put('/products/{id}', [\App\Http\Controllers\API\ProductController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/products/{id}', [\App\Http\Controllers\API\ProductController::class, 'destroy']);
Route::middleware('auth:sanctum')->post('/reviews', [\App\Http\Controllers\API\ReviewController::class, 'store']);
Route::get('/reviews/{product_id}', [\App\Http\Controllers\API\ReviewController::class, 'show']);
Route::middleware('auth:sanctum')->put('/reviews/{id}', [\App\Http\Controllers\API\ReviewController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/reviews/{id}', [\App\Http\Controllers\API\ReviewController::class, 'destroy']);
Route::get('/products/{id}/rating', [\App\Http\Controllers\API\ReviewController::class, 'ratingSummary']);
Route::post('/register', [\App\Http\Controllers\API\UserController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\API\UserController::class, 'login']);
Route::middleware('auth:sanctum')->get('/profile', [\App\Http\Controllers\API\UserController::class, 'profile']);
Route::middleware('auth:sanctum')->put('/profile', [\App\Http\Controllers\API\UserController::class, 'updateProfile']);
Route::middleware('auth:sanctum')->put('/change-password', [\App\Http\Controllers\API\UserController::class, 'changePassword']);
Route::post('/forgot-password', [\App\Http\Controllers\API\UserController::class, 'forgotPassword']);
Route::post('/reset-password', [\App\Http\Controllers\API\UserController::class, 'resetPassword']);
Route::middleware('auth:sanctum')->post('/logout', [\App\Http\Controllers\API\UserController::class, 'logout']);
Route::middleware('auth:sanctum')->post('/logout-all', [\App\Http\Controllers\API\UserController::class, 'logoutAll']);
// OTP login APIs
Route::post('/send-otp', [\App\Http\Controllers\UserController::class, 'sendOtp']);
Route::post('/verify-otp', [\App\Http\Controllers\UserController::class, 'verifyOtp']);
Route::middleware('auth:sanctum')->post('/complete-profile', [\App\Http\Controllers\UserController::class, 'completeProfile']);

// Google OAuth API
Route::post('/auth/google', [\App\Http\Controllers\GoogleAuthController::class, 'handleGoogleLogin']);

// Test endpoint for debugging
Route::post('/test-google', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'Test endpoint working',
        'received_credential' => !empty($request->input('credential')),
        'method' => $request->method(),
    ]);
});

// Payment Gateway APIs
Route::get('/payment/gateways', [\App\Http\Controllers\PaymentController::class, 'gateways']);
Route::post('/payment/initiate', [\App\Http\Controllers\PaymentController::class, 'initiate'])->middleware('auth:sanctum');
Route::post('/payment/callback/{gateway}', [\App\Http\Controllers\PaymentController::class, 'callback']);

// Password reset API
Route::post('/reset-password', [\App\Http\Controllers\UserController::class, 'resetPassword']);
// Forgot password API
Route::post('/forgot-password', [\App\Http\Controllers\UserController::class, 'forgotPassword']);
// Product image delete route
Route::delete('/product-images/{id}', [\App\Http\Controllers\API\ProductImageController::class, 'destroy']);

// Wishlist API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store']);
    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy']);
});

// Debug endpoints (development only - remove in production)
Route::get('/debug/order-schema', [\App\Http\Controllers\API\DebugController::class, 'orderSchema']);
Route::post('/debug/validate-order', [\App\Http\Controllers\API\DebugController::class, 'validateOrderPayload']);

// Health check endpoints (no auth required for monitoring)
Route::get('/health', [\App\Http\Controllers\API\HealthController::class, 'check']);
Route::get('/health/status', [\App\Http\Controllers\API\HealthController::class, 'status']);
