@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Inventory Valuation</h1>
    <div class="card mb-4">
        <div class="card-body">
            <h5>Total Inventory Value: <span class="text-success">৳ {{ number_format($totalValue, 2) }}</span></h5>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Current Stock</th>
                            <th>Cost Price</th>
                            <th>Inventory Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->stock_quantity }}</td>
                                <td>{{ $product->cost_price }}</td>
                                <td>৳ {{ number_format(($product->cost_price ?? 0) * ($product->stock_quantity ?? 0), 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
