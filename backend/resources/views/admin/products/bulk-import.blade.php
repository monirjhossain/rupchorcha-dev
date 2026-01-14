@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Product Bulk Import</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('products.bulkImport') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="import_file">Upload CSV File</label>
                    <input type="file" name="import_file" id="import_file" class="form-control" accept=".csv" required>
                </div>
                <button type="submit" class="btn btn-primary">Import Products</button>
            </form>
            <hr>
            <p class="mt-3">Download a sample CSV: <a href="{{ route('products.bulkImportSample') }}" class="btn btn-sm btn-info">Download Sample</a></p>
            <hr>
            <div class="alert alert-info mt-3">
                <strong>CSV Import Instructions:</strong><br>
                <ul class="mb-1">
                    <li><strong>Required fields:</strong> <code>name</code>, <code>sku</code>, <code>price</code>, <code>category</code>, <code>brand</code>, <code>type</code></li>
                    <li><strong>Optional fields:</strong> <code>sale_price</code>, <code>cost_price</code>, <code>stock_quantity</code>, <code>main_image</code></li>
                    <li>For <code>category</code> and <code>brand</code>, use the exact name (not ID) as in your system.</li>
                    <li>Example CSV columns: <code>name,sku,price,sale_price,cost_price,category,brand,stock_quantity,main_image,type</code></li>
                    <li>Download the sample CSV for correct format.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
