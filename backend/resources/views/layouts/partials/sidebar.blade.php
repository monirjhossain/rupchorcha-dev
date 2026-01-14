<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion sidebar-sticky" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin</div>
    </a>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="/admin">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <!-- Nav Item - Dashboard Report -->
    <!-- Nav Item - Report -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReport" aria-expanded="false" aria-controls="collapseReport">
            <i class="fas fa-chart-bar fa-fw"></i>
            <span>Report</span>
        </a>
        <div id="collapseReport" class="collapse" aria-labelledby="headingReport" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('report.brand') }}">Brand Wise Sales</a>
                <a class="collapse-item" href="{{ route('report.category') }}">Category Wise Sales</a>
                <a class="collapse-item" href="{{ route('report.total') }}">Total Sales</a>
                <a class="collapse-item" href="{{ route('report.max_products') }}">Max Selling Products</a>
            </div>
        </div>
    </li>
    <!-- Nav Item - Advanced Reports -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAdvancedReports" aria-expanded="false" aria-controls="collapseAdvancedReports">
            <i class="fas fa-chart-pie fa-fw"></i>
            <span>Advanced Reports</span>
        </a>
        <div id="collapseAdvancedReports" class="collapse" aria-labelledby="headingAdvancedReports" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('reports.index') }}">Reports & Analytics</a>
                <a class="collapse-item" href="{{ route('reports.sales') }}">Sales Analytics</a>
                <a class="collapse-item" href="{{ route('reports.revenue') }}">Revenue Analytics</a>
                <a class="collapse-item" href="{{ route('reports.top_products') }}">Top Products</a>
                <a class="collapse-item" href="{{ route('reports.customers') }}">Customer Analytics</a>
            </div>
        </div>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider">
    <!-- Reviews Nav Item -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReviews" aria-expanded="false" aria-controls="collapseReviews">
            <i class="fas fa-star fa-fw"></i>
            <span>Reviews</span>
        </a>
        <div id="collapseReviews" class="collapse" aria-labelledby="headingReviews" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('reviews.index') }}">Products Reviews</a>
                
            </div>
        </div>
    </li>
    <!-- Users Nav Item -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUsers" aria-expanded="false" aria-controls="collapseUsers">
            <i class="fas fa-users fa-fw"></i>
            <span>Users</span>
        </a>
        <div id="collapseUsers" class="collapse" aria-labelledby="headingUsers" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/admin/users">User List</a>
                <a class="collapse-item" href="{{ route('users.activity_logs') }}">User Activity Log</a>

            </div>
        </div>
    </li>
    <!-- Inventory Section -->
    <hr class="sidebar-divider">
    <div class="sidebar-heading">Inventory</div>
    <!-- Inventory Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInventory" aria-expanded="true" aria-controls="collapseInventory">
            <i class="fas fa-warehouse fa-fw"></i>
            <span>Inventory</span>
        </a>
        <div id="collapseInventory" class="collapse" aria-labelledby="headingInventory" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/admin/inventory">Inventory Dashboard</a>
                <a class="collapse-item" href="/admin/products">All Products</a>
                <a class="collapse-item" href="/admin/products/create">Create Product</a>
                <a class="collapse-item" href="/admin/categories">Categories</a>
                <a class="collapse-item" href="/admin/brands">Brands</a>
                <a class="collapse-item" href="/admin/attributes">Attributes</a>
                <a class="collapse-item" href="/admin/tags">Product Tags</a>
                <a class="collapse-item" href="/admin/products/bulk-import">Product Bulk Import</a>
                <div class="dropdown-divider"></div>
                <a class="collapse-item" href="{{ route('suppliers.index') }}">Suppliers</a>
                <a class="collapse-item" href="{{ route('warehouses.index') }}">Warehouses</a>
            </div>
        </div>
    </li>
    <!-- Shipping Section -->
    <hr class="sidebar-divider">
    <div class="sidebar-heading">Shipping</div>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseShipping" aria-expanded="false" aria-controls="collapseShipping">
            <i class="fas fa-shipping-fast fa-fw"></i>
            <span>Shipping</span>
        </a>
        <div id="collapseShipping" class="collapse" aria-labelledby="headingShipping" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('shipping-zones.index') }}">Shipping Zones</a>
                <a class="collapse-item" href="{{ route('shipping-methods.index') }}">Shipping Methods</a>
            </div>
        </div>
    </li>
    <!-- End Shipping Section -->
    <!-- Order Management Section -->
    <hr class="sidebar-divider">
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOrders" aria-expanded="false" aria-controls="collapseOrders">
            <i class="fas fa-shopping-cart fa-fw"></i>
            <span>Order Management</span>
        </a>
        <div id="collapseOrders" class="collapse" aria-labelledby="headingOrders" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('orders.index') }}">Orders</a>
                <a class="collapse-item" href="{{ route('coupons.index') }}">Coupon</a>
                <a class="collapse-item" href="{{ route('discounts.index') }}">Discount</a>
                <a class="collapse-item" href="{{ url('/admin/shipping-methods') }}">Shipping Methods</a>
                <a class="collapse-item" href="{{ route('shipping-methods.index') }}">Product Discount</a>
                <a class="collapse-item" href="{{ route('couriers.index') }}">Courier Management</a>
                <a class="collapse-item" href="{{ route('orders.bulkAssignCourierForm') }}">Bulk Courier Assign</a>
                <a class="collapse-item" href="{{ route('refunds.index') }}">Refund Policy</a>
                <a class="collapse-item" href="{{ route('addresses.index') }}">Customer Addresses</a>
                <!-- Future: Add more order-related links here -->
            </div>
        </div>
    </li>
        <!-- Purchase Order Management Section -->
    <hr class="sidebar-divider">
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePurchaseOrder" aria-expanded="false" aria-controls="collapsePurchaseOrder">
            <i class="fas fa-file-invoice fa-fw"></i>
            <span>Purchase Order</span>
        </a>
        <div id="collapsePurchaseOrder" class="collapse" aria-labelledby="headingPurchaseOrder" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('purchase_orders.index') }}">Purchase Orders</a>
                <!-- Future: Add more PO-related links here -->
            </div>
        </div>
    </li>
    <!-- Marketing Section -->
    <hr class="sidebar-divider">
    <div class="sidebar-heading">Marketing</div>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMarketing" aria-expanded="false" aria-controls="collapseMarketing">
            <i class="fas fa-bullhorn fa-fw"></i>
            <span>Marketing</span>
        </a>
        <div id="collapseMarketing" class="collapse" aria-labelledby="headingMarketing" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('bulk_email.index') }}">Bulk Email</a>
                <a class="collapse-item" href="{{ route('users.index') }}#bulk-sms">Bulk SMS</a>
                <a class="collapse-item" href="{{ route('campaign_history.index') }}">Campaign History</a>
            </div>
        </div>
    </li>
    <!-- Customers Book Section moved under Order Management -->
    <!-- Discount Management Section -->
    <hr class="sidebar-divider">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('discount-conditions.index') }}">
            <i class="fas fa-percent fa-fw"></i>
            <span>Advance Discount</span>
        </a>
    </li>
    <!-- Payment Section -->
    <hr class="sidebar-divider">
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePayment" aria-expanded="false" aria-controls="collapsePayment">
            <i class="fas fa-money-check-alt fa-fw"></i>
            <span>Payment</span>
        </a>
        <div id="collapsePayment" class="collapse" aria-labelledby="headingPayment" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('payments.summary') }}">Payment Summary</a>
                <a class="collapse-item" href="{{ route('payment-gateways.index') }}">Payment Gateways</a>
                <a class="collapse-item" href="{{ route('transactions.index') }}">Transactions</a>
            </div>
        </div>
    </li>
    <!-- More sidebar items can be added here -->
</ul>
<!-- End of Sidebar -->
