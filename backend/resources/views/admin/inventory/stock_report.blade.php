@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Stock Management</h1>
    <form method="GET" class="mb-3">
        <div class="form-row align-items-end">
            <div class="form-group col-md-3">
                <label for="brand_id">Brand</label>
                <select name="brand_id" id="brand_id" class="form-control">
                    <option value="">All Brands</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="search">Product Name</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control" placeholder="Search by product name">
            </div>
            <div class="form-group col-md-2">
                <button type="submit" class="btn btn-info btn-block mt-4">Filter</button>
            </div>
        </div>
    </form>
    <div class="mb-3">
        <a href="{{ route('inventory.stock_report.export', request()->only(['brand_id','category_id','search'])) }}" class="btn btn-success">
            <i class="fas fa-file-csv"></i> Export CSV
        </a>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>Stock Qty</th>
                            <th>Cost Price</th>
                            <th>Stock Value (Cost)</th>
                            <th>Sale Price</th>
                            <th>Stock Value (Sale)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->brand->name ?? '' }}</td>
                                <td>{{ $product->categories->pluck('name')->join(', ') }}</td>
                                <td>{{ $product->stock_quantity }}</td>
                                <td>{{ $product->cost_price }}</td>
                                <td>{{ number_format($product->stock_quantity * ($product->cost_price ?? 0), 2) }}</td>
                                <td>{{ $product->sale_price }}</td>
                                <td>{{ number_format($product->stock_quantity * ($product->sale_price ?? 0), 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center">No products found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
