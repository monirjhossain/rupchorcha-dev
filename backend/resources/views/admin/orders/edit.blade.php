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
                <!-- Customer Information (matches create page) -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-info text-white"><i class="fas fa-user-circle mr-2"></i> Customer Information</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="customer_name">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ $order->customer_name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="customer_email">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="customer_email" id="customer_email" class="form-control" value="{{ $order->customer_email }}" required>
                        </div>
                        <div class="form-group">
                            <label for="customer_phone">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="customer_phone" id="customer_phone" class="form-control" value="{{ $order->customer_phone }}" required>
                        </div>
                        <div class="form-group">
                            <label for="shipping_address">Address <span class="text-danger">*</span></label>
                            <input type="text" name="shipping_address" id="shipping_address" class="form-control" value="{{ $order->shipping_address }}" required>
                        </div>
                        <div class="form-group">
                            <label for="city">City / District <span class="text-danger">*</span></label>
                            <select name="city" id="city" class="form-control" required>
                                <option value="">Select City / District</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="area">Area <span class="text-danger">*</span></label>
                            <select name="area" id="area" class="form-control" required>
                                <option value="">Select Area</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="notes">Order Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3">{{ $order->notes }}</textarea>
                        </div>
                    </div>
                </div>
                <!-- Shipping Section removed as address is collected in Customer Information -->
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
                    <div class="card-header bg-white border-bottom-0 d-flex flex-column flex-md-row align-items-md-center justify-content-between p-4">
                        <div class="d-flex align-items-center mb-2 mb-md-0">
                            <span class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-3" style="width:44px;height:44px;font-size:1.5rem;">
                                <i class="fas fa-receipt"></i>
                            </span>
                            <div>
                                <div class="font-weight-bold h5 mb-0">Order #{{ $order->id }}</div>
                                <div class="small text-muted">Placed: {{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : '-' }}</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <select name="status" id="status" class="form-control form-control-sm mr-2" style="width:auto;min-width:140px;">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <span id="order-status-badge" class="badge badge-pill px-4 py-2 text-uppercase shadow-sm ml-2 {{
                                $order->status == 'completed' ? 'badge-success' : (
                                $order->status == 'cancelled' ? 'badge-danger' : 'badge-info')
                            }}" style="font-size:1rem;letter-spacing:1px;">{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="w-50"><i class="fas fa-money-bill-wave text-success mr-2"></i>Subtotal:</th>
                                    <td class="text-right font-weight-bold">৳<span id="subtotal-summary">{{ number_format($order->total, 2) }}</span></td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-shipping-fast text-info mr-2"></i>Shipping Charge:</th>
                                    <td class="text-right d-flex align-items-center justify-content-end">
                                        <div class="custom-control custom-checkbox mr-3" style="min-width: 140px;">
                                            <input class="custom-control-input" type="checkbox" id="free-shipping-checkbox">
                                            <label class="custom-control-label" for="free-shipping-checkbox" style="font-weight:400;">Free Delivery</label>
                                        </div>
                                        <input type="number" step="0.01" name="shipping_cost" id="shipping_cost" class="form-control d-inline-block w-auto text-right" value="{{ $order->shipping_cost }}" required style="max-width:120px;">
                                    </td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-ticket-alt text-warning mr-2"></i>Coupon Code:</th>
                                    <td class="text-right">
                                        <div class="input-group input-group-sm w-75 float-right">
                                            <input type="text" name="coupon_code" id="coupon_code" class="form-control" value="{{ $order->coupon_code }}" placeholder="Enter coupon code">
                                            <input type="hidden" id="coupon-amount" value="0">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary" id="apply-coupon-btn">Apply</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="coupon-discount-row" style="display:none;">
                                    <th class="h5 text-danger"><i class="fas fa-minus-circle mr-2"></i>Coupon Discount:</th>
                                    <td class="h5 text-right text-danger"><span id="coupon-discount-sign">-</span>৳<span id="coupon-discount-amount">0.00</span></td>
                                </tr>
                                <tr class="bg-light">
                                    <th class="h5"><i class="fas fa-calculator text-primary mr-2"></i>Total Price:</th>
                                    <td class="h4 text-right text-primary">৳<span id="grand-total-summary">{{ number_format($order->grand_total, 2) }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="px-4 py-3 border-top bg-light d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                            <div class="d-flex align-items-center mb-2 mb-md-0">
                                <span class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-2" style="width:32px;height:32px;font-size:1.1rem;">
                                    <i class="fas fa-user"></i>
                                </span>
                                <span class="small"><strong>User:</strong> 
                                    @if($order->user)
                                        <span class="text-primary font-weight-bold">{{ $order->user->name }}</span>
                                    @elseif($order->user_id)
                                        <span class="text-primary">User#{{ $order->user_id }}</span>
                                    @else
                                        <span class="text-danger">(No user info)</span>
                                    @endif
                                </span>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary shadow-sm font-weight-bold px-4">Update Order</button>
                                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary ml-2">Cancel</a>
                            </div>
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
$(function() {
    // Live update status badge on dropdown change
    const badge = $('#order-status-badge');
    const statusMap = {
        pending: {text: 'Pending', cls: 'badge-info'},
        processing: {text: 'Processing', cls: 'badge-info'},
        delivered: {text: 'Delivered', cls: 'badge-info'},
        completed: {text: 'Completed', cls: 'badge-success'},
        cancelled: {text: 'Cancelled', cls: 'badge-danger'}
    };
    $('#status').on('change', function() {
        const val = $(this).val();
        const info = statusMap[val] || statusMap['pending'];
        badge.text(info.text.toUpperCase());
        badge.removeClass('badge-info badge-success badge-danger').addClass(info.cls);
    });
});
</script>
<script>
$(function() {
    let prevShipping = $('#shipping_cost').val();
    $('#free-shipping-checkbox').on('change', function() {
        if ($(this).is(':checked')) {
            prevShipping = $('#shipping_cost').val();
            $('#shipping_cost').val(0).prop('readonly', true);
        } else {
            $('#shipping_cost').val(prevShipping).prop('readonly', false);
        }
        // Update grand total
        if (typeof updateGrandTotal === 'function') updateGrandTotal();
        updateGrandTotalSummary();
    });
    // Update grand total summary on relevant input changes
    // Listen to all relevant changes for live calculation
    $('#shipping_cost, #coupon_code').on('input', function() {
        updateGrandTotalSummary();
    });
    // Also update when product rows change (add/remove)
    $('#added-products-table').on('input change', 'input', function() {
        updateGrandTotalSummary();
    });
    function updateGrandTotalSummary() {
        // Live calculation: subtotal (product price), shipping, coupon
        let subtotal = 0;
        $('#added-products-table tbody tr').each(function() {
            subtotal += parseFloat($(this).find('.row-subtotal').text().replace(/[^\d.\-]/g, '')) || 0;
        });
        let shipping = parseFloat($('#shipping_cost').val()) || 0;
        let coupon = 0;
        const couponVal = $('#coupon_code').val();
        if (couponVal && !isNaN(parseFloat(couponVal))) {
            coupon = parseFloat(couponVal);
        }
        let grandTotal = subtotal + shipping - coupon;
        if (grandTotal < 0) grandTotal = 0;
        $('#grand-total-summary').text(grandTotal.toFixed(2));
    }
    // Initial sync
    updateGrandTotalSummary();
});
</script>
<script>
// Product data for autocomplete (server-side rendered)
const products = [
    @foreach(App\Models\Product::orderBy('name')->get() as $product)
        { id: {{ $product->id }}, name: @json($product->name), sku: @json($product->sku), price: {{ $product->price }} },
    @endforeach
];

// Bangladesh Districts and Areas Data (full, matching create page)
const bdLocations = {
    'Dhaka': ['Dhaka Sadar','Dhamrai','Dohar','Keraniganj','Nawabganj','Savar'],
    'Faridpur': ['Faridpur Sadar','Alfadanga','Bhanga','Boalmari','Madhukhali','Nagarkanda','Sadarpur','Saltha'],
    'Gazipur': ['Gazipur Sadar','Tongi','Kaliakair','Kapasia','Sreepur','Konabari'],
    'Gopalganj': ['Gopalganj Sadar','Kashiani','Kotalipara','Muksudpur','Tungipara'],
    'Kishoreganj': ['Kishoreganj Sadar','Bhairab','Hossainpur','Karimganj','Katiadi','Mithamain','Nikli','Austagram','Tarail','Itna','Pakundia','Bajitpur'],
    'Madaripur': ['Madaripur Sadar','Kalkini','Rajoir','Shibchar'],
    'Manikganj': ['Manikganj Sadar','Singair','Saturia','Shivalaya','Harirampur','Ghior','Daulatpur'],
    'Munshiganj': ['Munshiganj Sadar','Tongibari','Louhajang','Sirajdikhan','Sreenagar','Gazaria'],
    'Narayanganj': ['Narayanganj Sadar','Bandar','Rupganj','Sonargaon'],
    'Narsingdi': ['Narsingdi Sadar','Belabo','Monohardi','Palash','Raipura','Shibpur'],
    'Rajbari': ['Rajbari Sadar','Goalanda','Pangsha','Baliakandi','Kalukhali'],
    'Shariatpur': ['Shariatpur Sadar','Bhedarganj','Damudya','Gosairhat','Naria','Zanjira'],
    'Tangail': ['Tangail Sadar','Mirzapur','Sakhipur','Basail','Kalihati','Bhuapur','Gopalpur','Madhupur','Dhanbari','Delduar','Nagarpur'],
    'Bandarban': ['Bandarban Sadar','Thanchi','Ruma','Naikhongchhari','Rowangchhari','Lama','Alikadam'],
    'Brahmanbaria': ['Brahmanbaria Sadar','Ashuganj','Bancharampur','Bijoynagar','Kasba','Nabinagar','Nasirnagar','Sarail'],
    'Chandpur': ['Chandpur Sadar','Faridganj','Hajiganj','Kachua','Matlab North','Matlab South','Shahrasti'],
    'Chattogram': ['Kotwali','Chawkbazar','Pahartali','Halishahar','Bayazid','Chandgaon','Double Mooring','Khulshi','Bakalia','Patenga','Agrabad'],
    "Cox's Bazar": ["Cox's Bazar Sadar",'Chakaria','Kutubdia','Maheshkhali','Ramu','Teknaf','Ukhiya','Pekua'],
    'Feni': ['Feni Sadar','Chhagalnaiya','Daganbhuiyan','Parshuram','Sonagazi','Fulgazi'],
    'Khagrachhari': ['Khagrachhari Sadar','Dighinala','Matiranga','Panchhari','Mahalchhari','Manikchhari','Ramgarh','Lakshmichhari'],
    'Lakshmipur': ['Lakshmipur Sadar','Ramganj','Ramgati','Komolnagar','Raipur'],
    'Noakhali': ['Noakhali Sadar','Begumganj','Chatkhil','Companiganj','Hatia','Senbagh','Sonaimuri','Kabirhat','Subarnachar'],
    'Rangamati': ['Rangamati Sadar','Belaichhari','Borka','Juraichhari','Kawkhali','Langadu','Naniarchar','Rajasthali','Kaptai'],
    'Cumilla': ['Cumilla Sadar','Kotwali','Burichang','Daudkandi','Chandina','Laksam','Debidwar','Muradnagar','Homna','Meghna','Titas','Nangalkot'],
    'Bogura': ['Bogura Sadar','Adamdighi','Dhunat','Gabtali','Kahaloo','Nandigram','Sariakandi','Sherpur','Shibganj','Sonatola','Shajahanpur'],
    'Joypurhat': ['Joypurhat Sadar','Akkelpur','Kalai','Khetlal','Panchbibi'],
    'Naogaon': ['Naogaon Sadar','Atrai','Badalgachhi','Dhamoirhat','Manda','Mohadevpur','Niamatpur','Porsha','Raninagar','Sapahar'],
    'Natore': ['Natore Sadar','Bagatipara','Baraigram','Gurudaspur','Lalpur','Naldanga','Singra'],
    'Chapainawabganj': ['Chapainawabganj Sadar','Bholahat','Gomastapur','Nachole','Shibganj'],
    'Pabna': ['Pabna Sadar','Aminpur','Atgharia','Bera','Chatmohar','Faridpur','Ishwardi','Santhia','Sujanagar'],
    'Rajshahi': ['Rajshahi Sadar','Bagha','Charghat','Durgapur','Godagari','Mohanpur','Paba','Putia','Tanore'],
    'Sirajganj': ['Sirajganj Sadar','Belkuchi','Chauhali','Kamarkhanda','Kazipur','Raiganj','Shahjadpur','Tarash','Ullahpara'],
    'Bagerhat': ['Bagerhat Sadar','Chitalmari','Fakirhat','Kachua','Mongla','Morrelganj','Rampal','Sharankhola'],
    'Chuadanga': ['Chuadanga Sadar','Alamdanga','Damurhuda','Jibannagar'],
    'Jashore': ['Jessore Sadar','Abhaynagar','Bagherpara','Chaugachha','Jhikargachha','Keshabpur','Manirampur','Sharsha'],
    'Jhenaidah': ['Jhenaidah Sadar','Harinakunda','Kaliganj','Kotchandpur','Maheshpur','Shailkupa'],
    'Khulna': ['Khulna Sadar','Dighalia','Koyra','Paikgachha','Phultala','Rupsha','Terokhada','Batiaghata','Dakop'],
    'Kushtia': ['Kushtia Sadar','Bheramara','Daulatpur','Khoksa','Mirpur','Sheikhpara'],
    'Magura': ['Magura Sadar','Mohammadpur','Shalikha','Sreepur'],
    'Meherpur': ['Meherpur Sadar','Gangni','Mujibnagar'],
    'Narail': ['Narail Sadar','Kalia','Lohagara'],
    'Satkhira': ['Satkhira Sadar','Assasuni','Debhata','Kalaroa','Kaliganj','Shyamnagar','Tala'],
    'Barguna': ['Barguna Sadar','Amtali','Bamna','Betagi','Patharghata','Taltali'],
    'Barishal': ['Barishal Sadar','Agailjhara','Babuganj','Bakerganj','Banaripara','Gournadi','Hijla','Mehendiganj','Muladi','Wazirpur'],
    'Bhola': ['Bhola Sadar','Borhanuddin','Char Fasson','Daulatkhan','Lalmohan','Manpura','Tazumuddin'],
    'Jhalokati': ['Jhalokati Sadar','Kathalia','Nalchity','Rajapur'],
    'Patuakhali': ['Patuakhali Sadar','Bauphal','Dashmina','Dumki','Galachipa','Kalapara','Mirzaganj','Rangabali'],
    'Pirojpur': ['Pirojpur Sadar','Bhandaria','Kawkhali','Mathbaria','Nesarabad','Nazirpur'],
    'Habiganj': ['Habiganj Sadar','Ajmiriganj','Bahubal','Baniachang','Chunarughat','Lakhai','Madhabpur','Nabiganj','Shayestaganj'],
    'Maulvibazar': ['Maulvibazar Sadar','Barlekha','Juri','Kamalganj','Kulaura','Rajnagar','Sreemangal'],
    'Sunamganj': ['Sunamganj Sadar','Bishwamvarpur','Chhatak','Derai','Dharampasha','Dowarabazar','Jagannathpur','Jamalganj','Sullah','Tahirpur','Shalla'],
    'Sylhet': ['Sylhet Sadar','Balaganj','Beanibazar','Bishwanath','Companiganj','Dakshin Surma','Fenchuganj','Golapganj','Gowainghat','Jaintiapur','Kanaighat','Osmani Nagar'],
    'Jamalpur': ['Jamalpur Sadar','Bakshiganj','Dewanganj','Islampur','Madarganj','Melandaha','Sarishabari'],
    'Mymensingh': ['Mymensingh Sadar','Bhaluka','Dhobaura','Fulbaria','Gaffargaon','Gouripur','Haluaghat','Ishwarganj','Muktagacha','Nandail','Phulpur','Trishal'],
    'Netrokona': ['Netrokona Sadar','Atpara','Barhatta','Durgapur','Kalmakanda','Kendua','Madan','Mohanganj','Purbadhala'],
    'Sherpur': ['Sherpur Sadar','Jhenaigati','Nakla','Nalitabari','Sreebardi'],
    'Dinajpur': ['Dinajpur Sadar','Birampur','Birganj','Birol','Bochaganj','Chirirbandar','Fulbari','Ghoraghat','Hakimpur','Kaharole','Khansama','Nawabganj','Parbatipur'],
    'Gaibandha': ['Gaibandha Sadar','Fulchhari','Gobindaganj','Palashbari','Sadullapur','Saghata','Sundarganj'],
    'Kurigram': ['Kurigram Sadar','Bhurungamari','Chilmari','Phulbari','Nageshwari','Rajarhat','Raomari','Ulipur'],
    'Lalmonirhat': ['Lalmonirhat Sadar','Aditmari','Hatibandha','Kaliganj','Patgram'],
    'Nilphamari': ['Nilphamari Sadar','Dimla','Domar','Jaldhaka','Kishoreganj','Saidpur'],
    'Panchagarh': ['Panchagarh Sadar','Atwari','Boda','Debiganj','Tetulia'],
    'Rangpur': ['Rangpur Sadar','Badarganj','Gangachara','Kaunia','Mithapukur','Pirgachha','Pirganj','Taraganj'],
    'Thakurgaon': ['Thakurgaon Sadar','Baliadangi','Haripur','Pirganj','Ranishankail']
};

$(document).ready(function() {
    // Populate City dropdown
    const citySelect = $('#city');
    const areaSelect = $('#area');
    const districtList = Object.keys(bdLocations).sort();
    citySelect.html('<option value="">Select City / District</option>');
    districtList.forEach(district => {
        citySelect.append(`<option value="${district}">${district}</option>`);
    });
    // Set selected city if exists
    const selectedCity = '{{ $order->city }}';
    if(selectedCity) {
        citySelect.val(selectedCity);
        // Populate area dropdown
        areaSelect.html('<option value="">Select Area</option>');
        if(bdLocations[selectedCity]) {
            bdLocations[selectedCity].forEach(area => {
                areaSelect.append(`<option value="${area}">${area}</option>`);
            });
        }
        areaSelect.val('{{ $order->area }}');
    }
    // On city change, update area dropdown
    citySelect.on('change', function() {
        const selected = $(this).val();
        areaSelect.html('<option value="">Select Area</option>');
        if(selected && bdLocations[selected]) {
            bdLocations[selected].forEach(area => {
                areaSelect.append(`<option value="${area}">${area}</option>`);
            });
        }
        // Optionally reset area
        areaSelect.val('');
        updateShippingCost();
    });
    // On area change, update shipping cost
    areaSelect.on('change', function() {
        updateShippingCost();
    });
    // Shipping cost logic
    function updateShippingCost() {
        const city = citySelect.val();
        const area = areaSelect.val();
        let shippingCost = 150; // Default: Outside Dhaka
        let shippingMethod = 'outside_dhaka';
        if(city === 'Dhaka') {
            if(area === 'Dhaka Sadar') {
                shippingCost = 60;
            } else if(area) {
                shippingCost = 120;
            }
            shippingMethod = 'inside_dhaka';
        }
        $('#shipping_cost').val(shippingCost);
        $('#shipping_method').val(shippingMethod);
        updateGrandTotal();
    }

    // Coupon and totals logic (sync with create page)
    function updateTotalWithCoupon() {
        let total = 0;
        $('#added-products-table tbody tr').each(function() {
            total += parseFloat($(this).find('.row-subtotal').text()) || 0;
        });
        let coupon = 0;
        const couponVal = $('#coupon_code').val();
        if (couponVal && !isNaN(parseFloat(couponVal))) {
            coupon = parseFloat(couponVal);
        }
        let finalTotal = total - coupon;
        if (finalTotal < 0) finalTotal = 0;
        $('#total').val(finalTotal.toFixed(2));
    }
    function updateGrandTotal() {
        let subtotal = parseFloat($('#total').val()) || 0;
        let shipping = parseFloat($('#shipping_cost').val()) || 0;
        let coupon = 0;
        const couponVal = $('#coupon_code').val();
        if (couponVal && !isNaN(parseFloat(couponVal))) {
            coupon = parseFloat(couponVal);
        }
        let grandTotal = subtotal + shipping - coupon;
        if (grandTotal < 0) grandTotal = 0;
        $('#grand-total').val(grandTotal.toFixed(2));
    }
    // Coupon apply button
    $('#apply-coupon-btn').on('click', function() {
        event.preventDefault(); // Prevent form submit
        let couponVal = $('#coupon_code').val();
        let subtotal = 0;
        $('#added-products-table tbody tr').each(function() {
            subtotal += parseFloat($(this).find('.row-subtotal').text()) || 0;
        });
        if (!couponVal) {
            $('#coupon-discount-row').hide();
            $('#coupon-discount-amount').text('0.00');
            $('#coupon-discount-sign').text('-');
            updateGrandTotalSummary(subtotal, 0);
            return;
        }
        // AJAX to validate coupon
        $.ajax({
            url: '/admin/coupons/validate',
            method: 'POST',
            data: {
                code: couponVal,
                subtotal: subtotal,
                _token: $('input[name="_token"]').val()
            },
            success: function(res) {
                if (res.valid && res.discount > 0.009) {
                    $('#coupon-discount-row').show();
                    $('#coupon-discount-amount').text(res.discount.toFixed(2));
                    $('#coupon-discount-sign').text('-');
                    updateGrandTotalSummary(subtotal, res.discount);
                } else {
                    $('#coupon-discount-row').hide();
                    $('#coupon-discount-amount').text('0.00');
                    $('#coupon-discount-sign').text('-');
                    updateGrandTotalSummary(subtotal, 0);
                }
            },
            error: function() {
                $('#coupon-discount-row').hide();
                $('#coupon-discount-amount').text('0.00');
                $('#coupon-discount-sign').text('-');
                updateGrandTotalSummary(subtotal, 0);
            }
        });
        // Update grand total summary (UI)
        function updateGrandTotalSummary(subtotal, discount) {
            let shipping = parseFloat($('#shipping_cost').val()) || 0;
            let grandTotal = subtotal + shipping - discount;
            if (grandTotal < 0) grandTotal = 0;
            $('#grand-total-summary').text(grandTotal.toFixed(2));
            // Also update subtotal display if you want to keep it live:
            $("#subtotal-summary").text(subtotal.toFixed(2));
        }
    });
    // Update grand total on shipping or coupon change
    $('#shipping_cost').on('input', function() {
        updateGrandTotal();
    });
    $('#coupon_code').on('input', function() {
        updateGrandTotal();
    });
    // Initial total and grand total (fix: recalculate from all loaded rows)
    function recalcTotalsFromTable() {
        let total = 0;
        $('#added-products-table tbody tr').each(function() {
            total += parseFloat($(this).find('.row-subtotal').text()) || 0;
        });
        $('#total').val(total.toFixed(2));
        let shipping = parseFloat($('#shipping_cost').val()) || 0;
        let coupon = 0;
        const couponVal = $('#coupon_code').val();
        if (couponVal && !isNaN(parseFloat(couponVal))) {
            coupon = parseFloat(couponVal);
        }
        let grandTotal = total + shipping - coupon;
        if (grandTotal < 0) grandTotal = 0;
        $('#grand-total').val(grandTotal.toFixed(2));
    }
    recalcTotalsFromTable();
    // Product autocomplete and row logic (same as before)
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
        updateGrandTotal();
        // Clear input fields
        $('#product-search').val('');
        $('#product-id').val('');
        $('#input-unit-price').val('');
        $('#input-subtotal').val('');
        $('#product-search-list').hide();
        $('#input-quantity').val('1');
    });
    $('#added-products-table').on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
        recalcTotalsFromTable();
    });
    $('#product-search').on('blur', function() {
        setTimeout(() => $('#product-search-list').hide(), 200);
    });
});
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush
