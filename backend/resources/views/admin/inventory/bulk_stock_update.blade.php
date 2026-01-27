@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Bulk Stock Update</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- CSV Upload Form -->
    <div class="card mb-4">
        <div class="card-header">CSV Import</div>
        <div class="card-body">
            <form method="POST" action="{{ route('inventory.bulk_stock_update.submit') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="csv_file">Upload CSV file (columns: <b>sku</b> or <b>product_id</b> or <b>name</b>, and <b>quantity</b>)</label>
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
                    <form method="POST" action="{{ route('inventory.bulk_stock_update.submit') }}">
                        @csrf
                        <input type="hidden" name="apply_csv" value="1">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Product ID</th>
                                        <th>SKU</th>
                                        <th>Product Name</th>
                                        <th>Current Stock</th>
                                        <th>New Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($csvPreview['rows'] as $row)
                                        <tr>
                                            <td>{{ $row['product_id'] }}</td>
                                            <td>{{ $row['sku'] }}</td>
                                            <td>{{ $row['product_name'] }}</td>
                                            <td>{{ $row['current_stock'] }}</td>
                                            <td>{{ $row['quantity'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-success mt-2">Apply Bulk Update</button>
                    </form>
                @endif
            </div>
        </div>
    @endif

    <!-- Manual Table Update (AJAX will be added) -->
    <form id="bulk-stock-form" method="POST" action="{{ route('inventory.bulk_stock_update.submit') }}">
        @csrf
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Current Stock</th>
                        <th>New Stock Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->stock_quantity }}</td>
                            <td>
                                <input type="hidden" name="stocks[{{ $loop->index }}][product_id]" value="{{ $product->id }}">
                                <input type="number" name="stocks[{{ $loop->index }}][quantity]" class="form-control stock-input" min="0" value="{{ old('stocks.'.$loop->index.'.quantity', $product->stock_quantity) }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update Stocks</button>
    </form>
</div>
@endsection
