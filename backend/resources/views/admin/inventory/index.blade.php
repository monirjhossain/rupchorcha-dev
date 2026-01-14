@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><i class="fas fa-warehouse"></i> Inventory Management</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form method="GET" class="form-inline">
                <input type="text" name="name" class="form-control mr-2" placeholder="Product Name" value="{{ request('name') }}">
                <select name="stock_status" class="form-control mr-2">
                    <option value="">All Stock Status</option>
                    <option value="in_stock" @if(request('stock_status')=='in_stock') selected @endif>In Stock</option>
                    <option value="out_of_stock" @if(request('stock_status')=='out_of_stock') selected @endif>Out of Stock</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Current Stock</th>
                        <th>Stock Movements</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->current_stock }}</td>
                        <td>
                            <a href="{{ route('products.edit', $product->id) }}#stock-log" class="btn btn-sm btn-info">View Log</a>
                        </td>
                        <td>
                            <a href="{{ route('products.stock_movement.create', $product->id) }}" class="btn btn-sm btn-warning">Stock Movement</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">No products found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
