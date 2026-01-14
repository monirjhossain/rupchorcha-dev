<?php
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CampaignHistoryController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\AddressController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PaymentSummaryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardReportController;
use App\Http\Controllers\BulkEmailController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ProductDownloadController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscountConditionController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserActivityLogController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ShippingMethodController;
use App\Http\Controllers\ShippingZoneController;
use App\Http\Controllers\StockMovementController;


// Password reset routes
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::post('/admin/products/export', [ProductController::class, 'export'])->name('products.export');

// Inventory management UI
Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::get('/admin/inventory', [InventoryController::class, 'index'])->name('inventory.index');
});

// Customer Address Book (admin)
Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::resource('/admin/addresses', AddressController::class)->names('addresses');
    Route::get('/admin/addresses-export', [AddressController::class, 'export'])->name('addresses.export');
    Route::post('/admin/addresses/bulk-sms', [AddressController::class, 'bulk_sms'])->name('addresses.bulk_sms');
    // AJAX endpoint for address dropdown
    Route::get('/admin/addresses/ajax/by-user', [AddressController::class, 'ajaxByUser'])->name('addresses.ajax.byUser');
});
// Product Reviews (admin & API)
Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::get('/admin/reviews', [ProductReviewController::class, 'index'])->name('reviews.index');
    Route::get('/admin/reviews/{review}/edit', [ProductReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/admin/reviews/{review}', [ProductReviewController::class, 'update'])->name('reviews.update');
    Route::post('/admin/reviews/{review}/status', [ProductReviewController::class, 'updateStatus'])->name('reviews.updateStatus');
    Route::delete('/admin/reviews/{review}', [ProductReviewController::class, 'destroy'])->name('reviews.destroy');
});
// Product Reviews (customer API)
Route::post('/reviews', [ProductReviewController::class, 'store'])->name('reviews.store');

// Campaign History
Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::get('/admin/campaign-history', [CampaignHistoryController::class, 'index'])->name('campaign_history.index');
});

Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::get('/admin/bulk-email', [BulkEmailController::class, 'index'])->name('bulk_email.index');
    Route::post('/admin/bulk-email/send', [BulkEmailController::class, 'send'])->name('bulk_email.send');
});
// Report routes
Route::get('/report/brand', [DashboardReportController::class, 'brandReport'])->name('report.brand');
Route::get('/report/brand/export', [DashboardReportController::class, 'exportBrandReport'])->name('report.brand.export');
Route::get('/report/category', [DashboardReportController::class, 'categoryReport'])->name('report.category');
Route::get('/report/category/export', [DashboardReportController::class, 'exportCategoryReport'])->name('report.category.export');
Route::get('/report/total', [DashboardReportController::class, 'totalReport'])->name('report.total');
Route::get('/report/total/export', [DashboardReportController::class, 'exportTotalReport'])->name('report.total.export');
Route::get('/report/max-products', [DashboardReportController::class, 'maxProductsReport'])->name('report.max_products');
Route::get('/report/max-products/export', [DashboardReportController::class, 'exportMaxProductsReport'])->name('report.max_products.export');

// Advanced Reports & Analytics
Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::get('/admin/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/admin/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/admin/reports/sales/export/{type}', [ReportController::class, 'exportSales'])->name('reports.sales.export');
    Route::get('/admin/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/admin/reports/revenue/export/{type}', [ReportController::class, 'exportRevenue'])->name('reports.revenue.export');
    Route::get('/admin/reports/top-products', [ReportController::class, 'topProducts'])->name('reports.top_products');
    Route::get('/admin/reports/top-products/export/{type}', [ReportController::class, 'exportTopProducts'])->name('reports.top_products.export');
    Route::get('/admin/reports/customers', [ReportController::class, 'customers'])->name('reports.customers');
    Route::get('/admin/reports/customers/export/{type}', [ReportController::class, 'exportCustomers'])->name('reports.customers.export');
});

Route::get('/admin/users/activity-logs', [UserActivityLogController::class, 'index'])->name('users.activity_logs');
Route::get('/admin/users/{id}/activity-logs', [UserActivityLogController::class, 'show'])->name('users.activity_logs_user');
Route::get('/admin/payments/summary', [PaymentSummaryController::class, 'index'])->name('payments.summary');
Route::post('/admin/users/{id}/toggle', [UserController::class, 'toggleActive'])->name('users.toggle');
Route::post('/admin/users/{id}/message', [UserController::class, 'sendMessage'])->name('users.message');
Route::get('/admin/users/bulk-message', [UserController::class, 'bulkMessageForm'])->name('users.bulk_message_form');
Route::post('/admin/users/bulk-sms', [UserController::class, 'bulk_sms'])->name('users.bulk_sms');

// Supplier, Warehouse, and Purchase Order admin routes
Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::resource('/admin/suppliers', SupplierController::class)->names('suppliers');
    Route::resource('/admin/warehouses', WarehouseController::class)->names('warehouses');
    Route::get('/admin/purchase-orders/{purchase_order}/pdf', [PurchaseOrderController::class, 'pdf'])->name('purchase_orders.pdf');
    Route::get('/admin/purchase-orders/{purchase_order}/preview', [PurchaseOrderController::class, 'show'])->name('purchase_orders.preview');
    Route::resource('/admin/purchase-orders', PurchaseOrderController::class)->names('purchase_orders');
    Route::get('/admin/purchase-orders/{purchase_order}/show', [PurchaseOrderController::class, 'showDetails'])->name('purchase_orders.showDetails');
    Route::post('/admin/purchase-orders/{purchase_order}/update-status', [PurchaseOrderController::class, 'updateStatus'])->name('purchase_orders.updateStatus');
    Route::post('/admin/purchase-orders/{purchase_order}/approve', [PurchaseOrderController::class, 'approve'])->name('purchase_orders.approve');
    Route::post('/admin/purchase-orders/{purchase_order}/reject', [PurchaseOrderController::class, 'reject'])->name('purchase_orders.reject');
    Route::post('/admin/purchase-orders/{purchase_order}/cancel', [PurchaseOrderController::class, 'cancel'])->name('purchase_orders.cancel');
    Route::post('/admin/purchase-orders/{purchase_order}/receive-goods', [PurchaseOrderController::class, 'receiveGoods'])->name('purchase_orders.receiveGoods');
});

// Authentication routes
Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [UserController::class, 'register']);
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// User management (admin)
Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/admin/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('/admin/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/admin/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::post('/admin/users/{id}/delete', [UserController::class, 'destroy'])->name('users.destroy');
});
Route::get('/dashboard-report', [DashboardReportController::class, 'index'])->name('dashboard.report');
Route::get('/dashboard-report/export', [DashboardReportController::class, 'exportCsv'])->name('dashboard.report.export');

// Product module routes
Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::get('/admin/products/bulk-import', [ProductController::class, 'showBulkImportForm'])->name('products.bulkImportForm');
    Route::post('/admin/products/bulk-import', [ProductController::class, 'bulkImport'])->name('products.bulkImport');
    Route::get('/admin/products/bulk-import-sample', [ProductController::class, 'bulkImportSample'])->name('products.bulkImportSample');
    // Stock movement routes
    Route::get('/admin/products/{product}/stock-movement', [StockMovementController::class, 'create'])->name('products.stock_movement.create');
    Route::post('/admin/products/{product}/stock-movement', [StockMovementController::class, 'store'])->name('products.stock_movement.store');

    Route::resource('/admin/orders', OrderController::class);
        Route::get('/admin/orders/bulk-assign-courier', [OrderController::class, 'bulkAssignCourierForm'])->name('orders.bulkAssignCourierForm');
        Route::post('/admin/orders/bulk-assign-courier', [OrderController::class, 'bulkAssignCourier'])->name('orders.bulkAssignCourier');
    Route::resource('/admin/products', ProductController::class);
    Route::resource('/admin/categories', CategoryController::class);
    Route::resource('/admin/brands', BrandController::class);
    Route::resource('/admin/attributes', AttributeController::class);
});
Route::resource('/admin/attribute-values', AttributeValueController::class);
Route::resource('/admin/coupons', CouponController::class)->names('coupons');
Route::resource('discounts', DiscountController::class);


Route::resource('/admin/tags', TagController::class);
Route::resource('/admin/product-images', ProductImageController::class)->only(['index','create','store','destroy']);
Route::resource('/admin/product-downloads', ProductDownloadController::class)->only(['index','create','store','destroy']);

// Supplier, Warehouse, and Purchase Order admin routes
Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::resource('/admin/suppliers', SupplierController::class)->names('suppliers');
    Route::resource('/admin/warehouses', WarehouseController::class)->names('warehouses');
    Route::get('/admin/purchase-orders/{purchase_order}/pdf', [PurchaseOrderController::class, 'pdf'])->name('purchase_orders.pdf');
    Route::resource('/admin/purchase-orders',PurchaseOrderController::class)->names('purchase_orders');
}); 

// Shipping Methods & Zones (admin)
Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::resource('/admin/shipping-methods', ShippingMethodController::class);
    Route::resource('/admin/shipping-zones', ShippingZoneController::class);
    Route::resource('/admin/payment-gateways', \App\Http\Controllers\PaymentGatewayController::class)->names('payment-gateways');
    Route::resource('/admin/transactions', \App\Http\Controllers\TransactionController::class)->names('transactions');
    Route::resource('/admin/refunds', \App\Http\Controllers\RefundController::class)->names('refunds');
});

// Shipping: Add method to zone
Route::post('/admin/shipping-zones/{zone}/methods', [ShippingZoneController::class,'storeMethod'])->name('shipping-zones.methods.store');

// Shipping Method Conditions
Route::get('/admin/shipping-methods/{method}/conditions',[ShippingMethodController::class,'conditions'])->name('shipping-methods.conditions');
Route::post('/admin/shipping-methods/{method}/conditions', [ShippingMethodController::class,'storeCondition'])->name('shipping-methods.conditions.store');
Route::delete('/admin/shipping-methods/{method}/conditions/{condition}', [ShippingMethodController::class,'destroyCondition'])->name('shipping-methods.conditions.destroy');
// Discount Management Dashboard
Route::get('/admin/discounts/dashboard', function() {
    return view('admin.discounts.dashboard');
})->name('discounts.dashboard');

// Discount Condition Management
Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::get('/admin/discount-conditions', [DiscountConditionController::class, 'index'])->name('discount-conditions.index');
    Route::get('/admin/discount-conditions/create', [DiscountConditionController::class, 'create'])->name('discount-conditions.create');
    Route::post('/admin/discount-conditions', [DiscountConditionController::class, 'store'])->name('discount-conditions.store');
    Route::get('/admin/discount-conditions/{id}/edit', [DiscountConditionController::class, 'edit'])->name('discount-conditions.edit');
    Route::put('/admin/discount-conditions/{id}', [DiscountConditionController::class, 'update'])->name('discount-conditions.update');
    Route::delete('/admin/discount-conditions/{id}', [DiscountConditionController::class, 'destroy'])->name('discount-conditions.destroy');
});

// Courier management routes
Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::resource('/admin/couriers', CourierController::class)->names('couriers');
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root URL to login if not authenticated
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    return redirect('/admin/dashboard'); // Or your desired dashboard/home route
});

Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');

