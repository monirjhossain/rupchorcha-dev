@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><i class="fas fa-plus"></i> Create Purchase Order</h1>
    <form action="{{ route('purchase_orders.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">Purchase Order Details</div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Supplier <span class="text-danger">*</span></label>
                        <select name="supplier_id" class="form-control" required>
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Date <span class="text-danger">*</span></label>
                        <input type="date" name="order_date" class="form-control" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-info text-white">Products</div>
            <div class="card-body bg-light">
                <div class="row align-items-end mb-3" id="product-input-row">
                    <div class="form-group col-md-5 mb-2">
                        <label class="font-weight-bold">Product</label>
                        <select id="input-product-id" class="form-control product-select">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-cost-price="{{ $product->cost_price ?? 0 }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2 mb-2">
                        <label class="font-weight-bold">Quantity</label>
                        <input type="number" id="input-quantity" class="form-control" min="1" value="1">
                    </div>
                    <div class="form-group col-md-2 mb-2">
                        <label class="font-weight-bold">Unit Price</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">৳</span></div>
                            <input type="number" id="input-unit-price" class="form-control" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="form-group col-md-2 mb-2">
                        <label class="font-weight-bold">Subtotal</label>
                        <input type="text" id="input-subtotal" class="form-control bg-light font-weight-bold" readonly>
                    </div>
                    <div class="form-group col-md-1 mb-2 text-center">
                        <button type="button" class="btn btn-info" id="add-product-btn"><i class="fas fa-plus"></i> Add</button>
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
                <input type="hidden" id="products-data" name="products_data">
                <div class="form-row mt-3 align-items-center">
                    <div class="col-md-9 text-right pr-0"><span class="h5 font-weight-bold">Total:</span></div>
                    <div class="col-md-3">
                        <input type="text" id="order-total" class="form-control form-control-lg bg-white border-primary text-primary font-weight-bold" readonly name="order_total">
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-warning text-dark">Attachments & Status</div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Attachment (Invoice/Document)</label>
                        <input type="file" name="attachment" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="pending">Pending</option>
                            <option value="ordered">Ordered</option>
                            <option value="received">Received</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb-4">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save</button>
            <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary"><i class="fas fa-times mr-1"></i> Cancel</a>
        </div>
        @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            border-color: #17a2b8;
            box-shadow: 0 0 0 2px #17a2b855;
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
        </style>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
        $(document).ready(function() {
            function updateInputSubtotal() {
                const qty = parseFloat($('#input-quantity').val()) || 0;
                const price = parseFloat($('#input-unit-price').val()) || 0;
                $('#input-subtotal').val((qty * price).toFixed(2));
            }
            function updateOrderTotal() {
                let total = 0;
                $('#added-products-table tbody tr').each(function() {
                    total += parseFloat($(this).find('.row-subtotal').text()) || 0;
                });
                $('#order-total').val(total.toFixed(2));
            }
            function clearProductInputs() {
                $('#input-product-id').val('').trigger('change');
                $('#input-quantity').val('1');
                $('#input-unit-price').val('');
                $('#input-subtotal').val('');
            }
            $('.product-select').select2({
                placeholder: '🔍 Search or select product',
                allowClear: true,
                width: '100%'
            });
            $('#input-quantity, #input-unit-price').on('input', updateInputSubtotal);
            $('#input-product-id').on('change', function() {
                var cost = $(this).find('option:selected').data('cost-price');
                if (typeof cost !== 'undefined') {
                    $('#input-unit-price').val(cost);
                    updateInputSubtotal();
                }
            });
            $('#add-product-btn').on('click', function() {
                const productId = $('#input-product-id').val();
                const productText = $('#input-product-id option:selected').text();
                const qty = parseInt($('#input-quantity').val()) || 1;
                const price = parseFloat($('#input-unit-price').val()) || 0;
                const subtotal = (qty * price).toFixed(2);
                if (!productId || qty < 1 || price < 0) {
                    alert('Please select a product and enter valid quantity and price.');
                    return;
                }
                // Add row to table with hidden inputs for form submission
                const row = `<tr data-product-id="${productId}">
                    <td>
                        <input type="hidden" name="product_id[]" value="${productId}">
                        <input type="hidden" name="quantity[]" value="${qty}">
                        <input type="hidden" name="unit_price[]" value="${price}">
                        ${productText}
                    </td>
                    <td>${qty}</td>
                    <td>৳${price.toFixed(2)}</td>
                    <td class="row-subtotal">${subtotal}</td>
                    <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-trash"></i></button></td>
                </tr>`;
                $('#added-products-table tbody').append(row);
                updateOrderTotal();
                clearProductInputs();
            });
            $('#added-products-table').on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
                updateOrderTotal();
            });
            // Initial subtotal
            updateInputSubtotal();
        });
        </script>
        @endpush
    </form>
</div>
@endsection
