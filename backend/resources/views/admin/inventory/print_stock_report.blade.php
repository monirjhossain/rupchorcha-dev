@extends('layouts.print')
@section('content')
<div class="container">
    <h2>Printable Inventory Report</h2>
    <table border="1" cellpadding="6" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Product</th>
                <th>SKU</th>
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
        @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->brand->name ?? '' }}</td>
                <td>{{ $product->categories->pluck('name')->join(', ') }}</td>
                <td>{{ $product->stock_quantity }}</td>
                <td>{{ $product->cost_price }}</td>
                <td>{{ number_format($product->stock_quantity * ($product->cost_price ?? 0), 2) }}</td>
                <td>{{ $product->sale_price }}</td>
                <td>{{ number_format($product->stock_quantity * ($product->sale_price ?? 0), 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<script>window.onload = function() { window.print(); }</script>
@endsection
