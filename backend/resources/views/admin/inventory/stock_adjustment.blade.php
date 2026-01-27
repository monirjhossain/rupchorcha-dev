@extends('layouts.admin')

@section('content')
    <div class="card mb-4">
        <div class="card-header">Undo Last Adjustment</div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="undo_product_id">Product</label>
                    <select id="undo_product_id" class="form-control">
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (SKU: {{ $product->sku }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2 align-self-end">
                    <button id="undo-btn" class="btn btn-warning">Undo Last Adjustment</button>
                </div>
            </div>
            <div id="undo-feedback" class="mt-2"></div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const undoBtn = document.getElementById('undo-btn');
        const undoProduct = document.getElementById('undo_product_id');
        const undoFeedback = document.getElementById('undo-feedback');
        if (undoBtn && undoProduct) {
            undoBtn.addEventListener('click', function(e) {
                e.preventDefault();
                undoFeedback.innerHTML = '';
                const productId = undoProduct.value;
                if (!productId) {
                    undoFeedback.innerHTML = '<div class="alert alert-danger">Select a product first.</div>';
                    return;
                }
                fetch(`{{ url('/admin/inventory/stock-adjustment/undo/') }}/${productId}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        undoFeedback.innerHTML = `<div class='alert alert-success'>${data.message}</div>`;
                    } else {
                        undoFeedback.innerHTML = `<div class='alert alert-danger'>${data.message}</div>`;
                    }
                })
                .catch(() => {
                    undoFeedback.innerHTML = `<div class='alert alert-danger'>An error occurred. Please try again.</div>`;
                });
            });
        }
    });
    </script>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Stock Adjustment</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- CSV Upload Form -->
    <div class="card mb-4">
        <div class="card-header">CSV Import</div>
        <div class="card-body">
            <form method="POST" action="{{ route('inventory.stock_adjustment.submit') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="csv_file">Upload CSV file (columns: <b>sku</b> or <b>product_id</b> or <b>name</b>, <b>type</b> [increase|decrease|set], <b>quantity</b>, <b>reason</b> [optional])</label>
                    <input type="file" name="csv_file" id="csv_file" class="form-control-file" accept=".csv">
                </div>
                <button type="submit" class="btn btn-info mt-2">Preview CSV</button>
            </form>
        </div>
    </div>

    <!-- CSV Preview Section (to be filled by controller) -->
    @if(isset($csvPreview))
        <div class="card mb-4">
            <div class="card-header">CSV Preview</div>
            <div class="card-body">
                @if(count($csvPreview['errors']) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($csvPreview['errors'] as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(count($csvPreview['rows']) > 0)
                    <form id="csv-bulk-adjustment-form" method="POST" action="{{ route('inventory.stock_adjustment.submit') }}">
                        @csrf
                        <input type="hidden" name="apply_csv" value="1">
                        <input type="hidden" name="csv_rows" value='@json($csvPreview["rows"])'>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Product ID</th>
                                        <th>SKU</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Current Stock</th>
                                        <th>Quantity</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($csvPreview['rows'] as $row)
                                        <tr>
                                            <td>{{ $row['product_id'] }}</td>
                                            <td>{{ $row['sku'] }}</td>
                                            <td>{{ $row['product_name'] }}</td>
                                            <td>{{ $row['type'] }}</td>
                                            <td>{{ $row['current_stock'] }}</td>
                                            <td>{{ $row['quantity'] }}</td>
                                            <td>{{ $row['reason'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-success mt-2">Apply Bulk Adjustment</button>
                    </form>
                    <div id="csv-ajax-feedback" class="mt-3"></div>
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const csvForm = document.getElementById('csv-bulk-adjustment-form');
                        const feedback = document.getElementById('csv-ajax-feedback');
                        if (csvForm) {
                            csvForm.addEventListener('submit', function(e) {
                                e.preventDefault();
                                feedback.innerHTML = '';
                                const formData = new FormData(csvForm);
                                fetch(csvForm.action, {
                                    method: 'POST',
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': formData.get('_token'),
                                    },
                                    body: formData
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        feedback.innerHTML = `<div class='alert alert-success'>${data.message}</div>`;
                                        csvForm.reset();
                                    } else {
                                        let errors = '';
                                        if (data.errors) {
                                            errors = '<ul>' + Object.values(data.errors).map(e => `<li>${e}</li>`).join('') + '</ul>';
                                        }
                                        feedback.innerHTML = `<div class='alert alert-danger'>${data.message || 'Error'}${errors}</div>`;
                                    }
                                })
                                .catch(() => {
                                    feedback.innerHTML = `<div class='alert alert-danger'>An error occurred. Please try again.</div>`;
                                });
                            });
                        }
                    });
                    </script>
                @endif
            </div>
        </div>
    @endif
    <div class="card mb-4">
        <div class="card-body">
            <form id="stock-adjustment-form" method="POST" action="{{ route('inventory.stock_adjustment.submit') }}">
                @csrf
                <div class="form-row align-items-end">
                    <div class="form-group col-md-4">
                        <label for="product_id">Product</label>
                        <select name="product_id" id="product_id" class="form-control" required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} (SKU: {{ $product->sku }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="type">Type</label>
                        <select name="type" id="type" class="form-control" required>
                            <option value="increase">Increase</option>
                            <option value="decrease">Decrease</option>
                            <option value="set">Set</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="quantity">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="reason">Reason</label>
                        <input type="text" name="reason" id="reason" class="form-control" placeholder="Optional">
                    </div>
                    <div class="form-group col-md-1">
                        <button type="submit" class="btn btn-primary">Adjust</button>
                    </div>
                </div>
            </form>
            <div id="ajax-feedback" class="mt-3"></div>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('stock-adjustment-form');
            const feedback = document.getElementById('ajax-feedback');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                feedback.innerHTML = '';
                const formData = new FormData(form);
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': formData.get('_token'),
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        feedback.innerHTML = `<div class='alert alert-success'>${data.message}</div>`;
                        form.reset();
                    } else {
                        let errors = '';
                        if (data.errors) {
                            errors = '<ul>' + Object.values(data.errors).map(e => `<li>${e}</li>`).join('') + '</ul>';
                        }
                        feedback.innerHTML = `<div class='alert alert-danger'>${data.message || 'Error'}${errors}</div>`;
                    }
                })
                .catch(() => {
                    feedback.innerHTML = `<div class='alert alert-danger'>An error occurred. Please try again.</div>`;
                });
            });
        });
        </script>
    </div>
</div>
@endsection
