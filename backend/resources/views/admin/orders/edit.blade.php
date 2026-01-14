@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Order</h1>
    <form action="{{ route('orders.update', $order->id) }}" method="POST" id="order-edit-form">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Customer Selection -->
            <div class="col-lg-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white"><i class="fas fa-user mr-2"></i> Customer</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="user_id">Select Customer <span class="text-danger">*</span></label>
                            <select name="user_id" id="user_id" class="form-control select2" required>
                                <option value="">Select customer</option>
                                <option value="0" {{ $order->user_id == 0 ? 'selected' : '' }}>Guest</option>
                                @foreach(App\Models\User::orderBy('name')->get() as $user)
                                    <option value="{{ $user->id }}" {{ $order->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Shipping Address Section -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-info text-white"><i class="fas fa-shipping-fast mr-2"></i> Shipping Address</div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>First name</label>
                                <input type="text" name="shipping_first_name" class="form-control" value="{{ $order->shipping_first_name }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Last name</label>
                                <input type="text" name="shipping_last_name" class="form-control" value="{{ $order->shipping_last_name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Company</label>
                            <input type="text" name="shipping_company" class="form-control" value="{{ $order->shipping_company }}">
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Address line 1</label>
                                <input type="text" name="shipping_address_1" class="form-control" value="{{ $order->shipping_address_1 }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Address line 2</label>
                                <input type="text" name="shipping_address_2" class="form-control" value="{{ $order->shipping_address_2 }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>City</label>
                                <input type="text" name="shipping_city" class="form-control" value="{{ $order->shipping_city }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Postcode / ZIP</label>
                                <input type="text" name="shipping_postcode" class="form-control" value="{{ $order->shipping_postcode }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Country / Region</label>
                                <select name="shipping_country" class="form-control">
                                    <option value="Bangladesh" {{ $order->shipping_country == 'Bangladesh' ? 'selected' : '' }}>Bangladesh</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>State / County</label>
                                <select name="shipping_state" class="form-control">
                                    <option value="">Select an option...</option>
                                    <option value="Dhaka" {{ $order->shipping_state == 'Dhaka' ? 'selected' : '' }}>Dhaka</option>
                                    <option value="Chattogram" {{ $order->shipping_state == 'Chattogram' ? 'selected' : '' }}>Chattogram</option>
                                    <option value="Khulna" {{ $order->shipping_state == 'Khulna' ? 'selected' : '' }}>Khulna</option>
                                    <option value="Rajshahi" {{ $order->shipping_state == 'Rajshahi' ? 'selected' : '' }}>Rajshahi</option>
                                    <option value="Barisal" {{ $order->shipping_state == 'Barisal' ? 'selected' : '' }}>Barisal</option>
                                    <option value="Sylhet" {{ $order->shipping_state == 'Sylhet' ? 'selected' : '' }}>Sylhet</option>
                                    <option value="Rangpur" {{ $order->shipping_state == 'Rangpur' ? 'selected' : '' }}>Rangpur</option>
                                    <option value="Mymensingh" {{ $order->shipping_state == 'Mymensingh' ? 'selected' : '' }}>Mymensingh</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Email address</label>
                                <input type="email" name="shipping_email" class="form-control" value="{{ $order->shipping_email }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Phone</label>
                                <input type="text" name="shipping_phone" class="form-control" value="{{ $order->shipping_phone }}">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Billing Address Section -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-secondary text-white"><i class="fas fa-file-invoice mr-2"></i> Billing Address</div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>First name</label>
                                <input type="text" name="billing_first_name" class="form-control" value="{{ $order->billing_first_name }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Last name</label>
                                <input type="text" name="billing_last_name" class="form-control" value="{{ $order->billing_last_name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Company</label>
                            <input type="text" name="billing_company" class="form-control" value="{{ $order->billing_company }}">
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Address line 1</label>
                                <input type="text" name="billing_address_1" class="form-control" value="{{ $order->billing_address_1 }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Address line 2</label>
                                <input type="text" name="billing_address_2" class="form-control" value="{{ $order->billing_address_2 }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>City</label>
                                <input type="text" name="billing_city" class="form-control" value="{{ $order->billing_city }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Postcode / ZIP</label>
                                <input type="text" name="billing_postcode" class="form-control" value="{{ $order->billing_postcode }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Country / Region</label>
                                <select name="billing_country" class="form-control">
                                    <option value="Bangladesh" {{ $order->billing_country == 'Bangladesh' ? 'selected' : '' }}>Bangladesh</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>State / County</label>
                                <select name="billing_state" class="form-control">
                                    <option value="">Select an option...</option>
                                    <option value="Dhaka" {{ $order->billing_state == 'Dhaka' ? 'selected' : '' }}>Dhaka</option>
                                    <option value="Chattogram" {{ $order->billing_state == 'Chattogram' ? 'selected' : '' }}>Chattogram</option>
                                    <option value="Khulna" {{ $order->billing_state == 'Khulna' ? 'selected' : '' }}>Khulna</option>
                                    <option value="Rajshahi" {{ $order->billing_state == 'Rajshahi' ? 'selected' : '' }}>Rajshahi</option>
                                    <option value="Barisal" {{ $order->billing_state == 'Barisal' ? 'selected' : '' }}>Barisal</option>
                                    <option value="Sylhet" {{ $order->billing_state == 'Sylhet' ? 'selected' : '' }}>Sylhet</option>
                                    <option value="Rangpur" {{ $order->billing_state == 'Rangpur' ? 'selected' : '' }}>Rangpur</option>
                                    <option value="Mymensingh" {{ $order->billing_state == 'Mymensingh' ? 'selected' : '' }}>Mymensingh</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Email address</label>
                                <input type="email" name="billing_email" class="form-control" value="{{ $order->billing_email }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Phone</label>
                                <input type="text" name="billing_phone" class="form-control" value="{{ $order->billing_phone }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Payment method</label>
                                <select name="payment_method" class="form-control" required>
                                    <option value="Bkash" {{ $order->payment_method == 'Bkash' ? 'selected' : '' }}>Bkash</option>
                                    <option value="Cash" {{ $order->payment_method == 'Cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="Nagad" {{ $order->payment_method == 'Nagad' ? 'selected' : '' }}>Nagad</option>
                                    <option value="Bank" {{ $order->payment_method == 'Bank' ? 'selected' : '' }}>Bank</option>
                                    <option value="Master Card" {{ $order->payment_method == 'Master Card' ? 'selected' : '' }}>Master Card</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Transaction ID</label>
                                <input type="text" name="transaction_id" class="form-control" value="{{ $order->transaction_id }}">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Payment Section -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-success text-white"><i class="fas fa-credit-card mr-2"></i> Payment</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="payment_status">Payment Status</label>
                            <select name="payment_status" id="payment_status" class="form-control" required>
                                <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Courier Selection -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-dark text-white"><i class="fas fa-truck mr-2"></i> Courier</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="courier_id">Select Courier <span class="text-danger">*</span></label>
                            <select name="courier_id" id="courier_id" class="form-control select2" required>
                                <option value="">Select courier</option>
                                @foreach(App\Models\Courier::orderBy('name')->get() as $courier)
                                    <option value="{{ $courier->id }}" {{ $order->courier_id == $courier->id ? 'selected' : '' }}>{{ $courier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Order Items Section -->
            <div class="col-lg-8">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-warning text-dark"><i class="fas fa-boxes mr-2"></i> Order Items</div>
                    <div class="card-body">
                        <div class="row align-items-end mb-3" id="product-input-row">
                            <div class="form-group col-md-5 mb-2">
                                <label class="font-weight-bold" for="product-search">Product</label>
                                <input type="text" id="product-search" class="form-control" placeholder="Type product name/SKU" autocomplete="off">
                                <input type="hidden" id="product-id" value="">
                                <div id="product-search-list" class="list-group position-absolute w-100" style="z-index: 1000; display: none;"></div>
                            </div>
                            <div class="form-group col-md-2 mb-2">
                                <label class="font-weight-bold">Quantity</label>
                                <input type="number" id="input-quantity" class="form-control" min="1" value="1">
                            </div>
                            <div class="form-group col-md-2 mb-2">
                                <label class="font-weight-bold">Unit Price</label>
                                <input type="number" id="input-unit-price" class="form-control" min="0" step="0.01">
                            </div>
                            <div class="form-group col-md-2 mb-2">
                                <label class="font-weight-bold">Subtotal</label>
                                <input type="text" id="input-subtotal" class="form-control bg-light font-weight-bold" readonly>
                            </div>
                            <div class="form-group col-md-1 mb-2 text-center">
                                <!-- Add button removed: product is added on click from dropdown -->
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover bg-white" id="added-products-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr data-product-id="{{ $item->product_id }}">
                                        <td>
                                            <input type="hidden" name="product_id[]" value="{{ $item->product_id }}">
                                            <input type="hidden" name="quantity[]" value="{{ $item->quantity }}">
                                            <input type="hidden" name="unit_price[]" value="{{ $item->price }}">
                                            {{ $item->product_name }}
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>৳{{ number_format($item->price, 2) }}</td>
                                        <td class="row-subtotal">{{ number_format($item->price * $item->quantity, 2) }}</td>
                                        <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-trash"></i></button></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Order Summary -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-light"><i class="fas fa-receipt mr-2"></i> Order Summary</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Order Status</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="coupon_code">Coupon Code</label>
                                    <div class="input-group">
                                        <input type="text" name="coupon_code" id="coupon_code" class="form-control" value="{{ $order->coupon_code }}" placeholder="Enter coupon code">
                                        <input type="hidden" id="coupon-amount" value="0">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" id="apply-coupon-btn">Apply</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total">Subtotal</label>
                                    <input type="number" step="0.01" name="total" id="total" class="form-control" value="{{ $order->total }}" readonly required>
                                </div>
                                <div class="form-group">
                                    <label for="grand-total">Grand Total</label>
                                    <input type="number" step="0.01" name="grand_total" id="grand-total" class="form-control bg-light font-weight-bold" value="{{ $order->grand_total }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Update Order</button>
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Product data for autocomplete (server-side rendered)
const products = [
    @foreach(App\Models\Product::orderBy('name')->get() as $product)
        { id: {{ $product->id }}, name: @json($product->name), sku: @json($product->sku), price: {{ $product->price }} },
    @endforeach
];

$(document).ready(function() {
    function updateTotalWithCoupon() {
        let total = 0;
        $('#added-products-table tbody tr').each(function() {
            total += parseFloat($(this).find('.row-subtotal').text()) || 0;
        });
        let coupon = parseFloat($('#coupon-amount').val()) || 0;
        let grandTotal = total - coupon;
        if (grandTotal < 0) grandTotal = 0;
        $('#total').val(total.toFixed(2));
        $('#grand-total').val(grandTotal.toFixed(2));
    }

    // Apply coupon only when button is clicked
    $('#apply-coupon-btn').on('click', function() {
        let code = $('#coupon_code').val().trim();
        let couponValue = 0;
        if (code === 'User123') {
            couponValue = 100; // Example: 100 off
        } else {
            couponValue = 0;
        }
        $('#coupon-amount').val(couponValue);
        updateTotalWithCoupon();
    });

    // Add product row (from autocomplete/typeahead)
    $('#product-search-list').on('click', 'button', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const price = $(this).data('price');
        const qty = parseInt($('#input-quantity').val()) || 1;
        const subtotal = (qty * price).toFixed(2);
        const row = `<tr data-product-id="${id}">
            <td>
                <input type="hidden" name="product_id[]" value="${id}">
                <input type="hidden" name="quantity[]" value="${qty}">
                <input type="hidden" name="unit_price[]" value="${price}">
                ${name}
            </td>
            <td>${qty}</td>
            <td>৳${price.toFixed(2)}</td>
            <td class="row-subtotal">${subtotal}</td>
            <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-trash"></i></button></td>
        </tr>`;
        $('#added-products-table tbody').append(row);
        updateTotalWithCoupon();
        // Clear input fields
        $('#product-search').val('');
        $('#product-id').val('');
        $('#input-unit-price').val('');
        $('#input-subtotal').val('');
        $('#product-search-list').hide();
        $('#input-quantity').val('1');
    });

    // Remove product row
    $('#added-products-table').on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
        updateTotalWithCoupon();
    });

    // Autocomplete for product search
    $('#product-search').on('input', function() {
        const query = $(this).val().toLowerCase();
        if (!query) {
            $('#product-search-list').hide();
            return;
        }
        const matches = products.filter(p =>
            (p.name && p.name.toLowerCase().includes(query)) ||
            (p.sku && p.sku.toLowerCase().includes(query))
        );
        if (matches.length === 0) {
            $('#product-search-list').hide();
            return;
        }
        let html = '';
        matches.forEach(p => {
            html += `<button type="button" class="list-group-item list-group-item-action" data-id="${p.id}" data-name="${p.name}" data-price="${p.price}">${p.name} <span class='text-muted'>(SKU: ${p.sku ? p.sku : 'N/A'}, ৳${p.price})</span></button>`;
        });
        $('#product-search-list').html(html).show();
    });

    // Hide dropdown on blur (with delay for click)
    $('#product-search').on('blur', function() {
        setTimeout(() => $('#product-search-list').hide(), 200);
    });

    // Initial total
    updateTotalWithCoupon();
});
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush
