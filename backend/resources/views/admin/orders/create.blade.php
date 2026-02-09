@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h2 class="mb-3" style="font-size:1.5rem;font-weight:600;">Create Order</h2>
    <form action="{{ route('orders.store') }}" method="POST" id="order-create-form">
        @csrf
        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-3 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 p-2" style="font-weight:600;font-size:1.1rem;">Customer & Shipping</div>
                    <div class="card-body p-2">
                        <div class="form-group mb-2">
                            <label for="user_id" class="mb-1">Customer <span class="text-danger">*</span></label>
                            <select name="user_id" id="user_id" class="form-control form-control-sm select2" required>
                                <option value="">Select customer</option>
                                <option value="0">Guest</option>
                                @foreach(App\Models\User::orderBy('name')->get() as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="customer_name" class="mb-1">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" id="customer_name" class="form-control form-control-sm" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="customer_email" class="mb-1">Email <span class="text-danger">*</span></label>
                            <input type="email" name="customer_email" id="customer_email" class="form-control form-control-sm" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="customer_phone" class="mb-1">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="customer_phone" id="customer_phone" class="form-control form-control-sm" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="shipping_address" class="mb-1">Address <span class="text-danger">*</span></label>
                            <input type="text" name="shipping_address" id="shipping_address" class="form-control form-control-sm" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 mb-2">
                                <label for="city" class="mb-1">City/District <span class="text-danger">*</span></label>
                                <select name="city" id="city" class="form-control form-control-sm" required>
                                    <option value="">Select City / District</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6 mb-2">
                                <label for="area" class="mb-1">Area <span class="text-danger">*</span></label>
                                <select name="area" id="area" class="form-control form-control-sm" required>
                                    <option value="">Select Area</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <label for="notes" class="mb-1">Order Notes</label>
                            <textarea name="notes" id="notes" class="form-control form-control-sm" rows="2"></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 mb-2">
                                <label for="payment_method" class="mb-1">Payment <span class="text-danger">*</span></label>
                                <select name="payment_method" id="payment_method" class="form-control form-control-sm" required>
                                    <option value="cod">Cash on Delivery</option>
                                    <option value="bkash">Bkash</option>
                                    <option value="nagad">Nagad</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6 mb-2">
                                <label for="shipping_method" class="mb-1">Shipping <span class="text-danger">*</span></label>
                                <select name="shipping_method" id="shipping_method" class="form-control form-control-sm" required>
                                    <option value="inside_dhaka">Inside Dhaka</option>
                                    <option value="outside_dhaka">Outside Dhaka</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <label for="shipping_cost" class="mb-1">Shipping Cost (৳) <span class="text-danger">*</span></label>
                            <input type="number" name="shipping_cost" id="shipping_cost" class="form-control form-control-sm" step="0.01" required>
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
                                <tbody></tbody>
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
                                        <option value="pending">Pending</option>
                                        <option value="processing">Processing</option>
                                        <option value="completed">Completed</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="payment_status">Payment Status <span class="text-danger">*</span></label>
                                    <select name="payment_status" id="payment_status" class="form-control" required>
                                        <option value="">Select status</option>
                                        <option value="unpaid">Unpaid</option>
                                        <option value="paid">Paid</option>
                                        <option value="pending">Pending</option>
                                        <option value="refunded">Refunded</option>
                                    </select>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="coupon_code">Coupon Code</label>
                                    <div class="input-group">
                                        <input type="text" name="coupon_code" id="coupon_code" class="form-control" placeholder="Enter coupon code">
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
                                    <input type="number" step="0.01" name="total" id="total" class="form-control" readonly required>
                                </div>
                                <div class="form-group">
                                    <label for="grand-total">Grand Total</label>
                                    <input type="number" step="0.01" name="grand_total" id="grand-total" class="form-control bg-light font-weight-bold" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Create Order</button>
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
        // Coupon logic: subtract coupon value from total
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

        // Only apply coupon when button is clicked
        $('#apply-coupon-btn').on('click', function() {
            updateTotalWithCoupon();
        });

        // Update total when products change
        // This handler was causing duplicate events - removed to prevent double-add

        // Also update total if a row is removed
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

    // Select product from dropdown
    $('#product-search-list').on('click', 'button', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const price = $(this).data('price');
        const qty = parseInt($('#input-quantity').val()) || 1;
        const subtotal = (qty * price).toFixed(2);
        // Add row to table with hidden fields
        const row = `<tr data-product-id="${id}">
            <td>
                <input type="hidden" name="products[]" value="${id}">
                <input type="hidden" name="quantities[]" class="quantity-hidden" value="${qty}">
                <input type="hidden" name="prices[]" value="${price}">
                ${name}
            </td>
            <td class="quantity-cell">
                <div class="input-group input-group-sm" style="width: 120px;">
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-secondary qty-decrease" type="button" title="Decrease">−</button>
                    </div>
                    <input type="text" class="form-control text-center quantity-input" value="${qty}" readonly>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary qty-increase" type="button" title="Increase">+</button>
                    </div>
                </div>
            </td>
            <td>৳${price.toFixed(2)}</td>
            <td class="row-subtotal">${subtotal}</td>
            <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-trash"></i></button></td>
        </tr>`;
        $('#added-products-table tbody').append(row);
        // Optionally update total
        let total = 0;
        $('#added-products-table tbody tr').each(function() {
            total += parseFloat($(this).find('.row-subtotal').text()) || 0;
        });
        $('#total').val(total.toFixed(2));
        updateGrandTotal();
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
        updateGrandTotal();
    });

    // Handle quantity increase
    $('#added-products-table').on('click', '.qty-increase', function() {
        const $row = $(this).closest('tr');
        const $quantityInput = $row.find('.quantity-input');
        const $hiddenQuantity = $row.find('.quantity-hidden');
        const $unitPrice = parseFloat($row.find('td:eq(2)').text().replace('৳', ''));
        const currentQty = parseInt($quantityInput.val()) || 1;
        const newQty = currentQty + 1;
        
        $quantityInput.val(newQty);
        $hiddenQuantity.val(newQty);
        
        // Update subtotal
        const subtotal = (newQty * $unitPrice).toFixed(2);
        $row.find('.row-subtotal').text(subtotal);
        
        // Update total
        updateTotalWithCoupon();
    });

    // Handle quantity decrease
    $('#added-products-table').on('click', '.qty-decrease', function() {
        const $row = $(this).closest('tr');
        const $quantityInput = $row.find('.quantity-input');
        const $hiddenQuantity = $row.find('.quantity-hidden');
        const $unitPrice = parseFloat($row.find('td:eq(2)').text().replace('৳', ''));
        const currentQty = parseInt($quantityInput.val()) || 1;
        const newQty = Math.max(1, currentQty - 1);
        
        $quantityInput.val(newQty);
        $hiddenQuantity.val(newQty);
        
        // Update subtotal
        const subtotal = (newQty * $unitPrice).toFixed(2);
        $row.find('.row-subtotal').text(subtotal);
        
        // Update total
        updateTotalWithCoupon();
    });

    // Grand total calculation
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

    // Initial total
    updateTotalWithCoupon();
    updateGrandTotal();
    // Update grand total on shipping or coupon change
    $('#shipping_cost').on('input', function() {
        updateGrandTotal();
    });
    $('#coupon_code').on('input', function() {
        updateGrandTotal();
    });

    // Bangladesh Districts and Areas Data
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

    // Populate City dropdown
    const citySelect = $('#city');
    const districtList = Object.keys(bdLocations).sort();
    districtList.forEach(district => {
        citySelect.append(`<option value="${district}">${district}</option>`);
    });

    // Handle City change to populate Area dropdown
    $('#city').on('change', function() {
        const selectedCity = $(this).val();
        const areaSelect = $('#area');
        areaSelect.html('<option value="">Select Area</option>');
        
        if (selectedCity && bdLocations[selectedCity]) {
            bdLocations[selectedCity].forEach(area => {
                areaSelect.append(`<option value="${area}">${area}</option>`);
            });
        }

        // Auto-calculate shipping cost based on city and area
        updateShippingCost();
    });

    // Update shipping cost when area changes
    $('#area').on('change', function() {
        updateShippingCost();
    });

    function updateShippingCost() {
        const city = $('#city').val();
        const area = $('#area').val();
        
        if (city && area) {
            let shippingCost = 150; // Default: Outside Dhaka
            let shippingMethod = 'outside_dhaka';
            
            if (city === 'Dhaka') {
                if (area === 'Dhaka Sadar') {
                    shippingCost = 60;
                } else {
                    shippingCost = 120;
                }
                shippingMethod = 'inside_dhaka';
            }
            
            $('#shipping_cost').val(shippingCost);
            $('#shipping_method').val(shippingMethod);
        }
    }
});
</script>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    function fetchAddresses(userId, type, selectId) {
        if (!userId || userId == 0) {
            $(selectId).html('<option value="">-- Select address --</option>');
            return;
        }
        $.ajax({
            url: "{{ route('addresses.ajax.byUser') }}",
            data: { user_id: userId },
            success: function(response) {
                let options = '<option value="">-- Select address --</option>';
                if (response.success && response.data) {
                    response.data.forEach(function(addr) {
                        if (!type || addr.type === type) {
                            options += `<option value="${addr.id}" data-address='${JSON.stringify(addr)}'>${addr.address_line1}, ${addr.city}, ${addr.country}</option>`;
                        }
                    });
                }
                $(selectId).html(options);
            }
        });
    }

    $('#user_id').on('change', function() {
        let userId = $(this).val();
        fetchAddresses(userId, 'shipping', '#shipping_address_select');
        fetchAddresses(userId, 'billing', '#billing_address_select');
    });

    $('#shipping_address_select').on('change', function() {
        let selected = $(this).find('option:selected').data('address');
        if (selected) {
            $('input[name="shipping_first_name"]').val(selected.name || '');
            $('input[name="shipping_last_name"]').val('');
            $('input[name="shipping_company"]').val('');
            $('input[name="shipping_address_1"]').val(selected.address_line1 || '');
            $('input[name="shipping_address_2"]').val(selected.address_line2 || '');
            $('input[name="shipping_city"]').val(selected.city || '');
            $('input[name="shipping_postcode"]').val(selected.postal_code || '');
            $('input[name="shipping_country"]').val(selected.country || '');
            $('input[name="shipping_state"]').val(selected.state || '');
            $('input[name="shipping_email"]').val(selected.email || '');
            $('input[name="shipping_phone"]').val(selected.phone || '');
        }
    });
    $('#billing_address_select').on('change', function() {
        let selected = $(this).find('option:selected').data('address');
        if (selected) {
            $('input[name="billing_first_name"]').val(selected.name || '');
            $('input[name="billing_last_name"]').val('');
            $('input[name="billing_company"]').val('');
            $('input[name="billing_address_1"]').val(selected.address_line1 || '');
            $('input[name="billing_address_2"]').val(selected.address_line2 || '');
            $('input[name="billing_city"]').val(selected.city || '');
            $('input[name="billing_postcode"]').val(selected.postal_code || '');
            $('input[name="billing_country"]').val(selected.country || '');
            $('input[name="billing_state"]').val(selected.state || '');
            $('input[name="billing_email"]').val(selected.email || '');
            $('input[name="billing_phone"]').val(selected.phone || '');
        }
    });

    // On page load, if a customer is pre-selected, trigger address fetch
    let initialUserId = $('#user_id').val();
    if (initialUserId && initialUserId != 0) {
        fetchAddresses(initialUserId, 'shipping', '#shipping_address_select');
        fetchAddresses(initialUserId, 'billing', '#billing_address_select');
    }
});
</script>
@endpush

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 48px;
        border-radius: 0.5rem;
        border: 1.5px solid #ced4da;
        font-size: 1.1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default .select2-selection--single:hover {
        border-color: #ffc107;
        box-shadow: 0 0 0 2px #ffc10755;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 48px;
        color: #495057;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 48px;
    }
    .select2-search--dropdown .select2-search__field {
        border-radius: 0.5rem;
    }
    .select2-container {
        z-index: 9999 !important;
    }
    .select2-dropdown {
        z-index: 99999 !important;
    }
    .select2-results__options {
        max-height: 300px;
        overflow-y: auto;
    }
    #order-items-table th, #order-items-table td {
        vertical-align: middle;
        text-align: center;
    }
    #order-items-table th {
        background: #ffe082;
        color: #333;
        font-weight: 600;
    }
    #order-items-table td {
        background: #fffde7;
    }
    #add-product-btn {
        height: 40px;
        width: 100%;
        font-size: 1.1rem;
        border-radius: 0.5rem;
    }
    .card-header.bg-warning {
        background: linear-gradient(90deg, #ffe082 60%, #ffd54f 100%) !important;
        color: #333 !important;
    }
    .form-group label.font-weight-bold {
        font-size: 1.05rem;
        color: #333;
    }
</style>
@endpush
