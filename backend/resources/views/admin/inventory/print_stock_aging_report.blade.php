@extends('layouts.print')
@section('content')
<div class="container">
    <h2>Printable Stock Aging Report</h2>
    <table border="1" cellpadding="6" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Product</th>
                <th>SKU</th>
                <th>Brand</th>
                <th>Category</th>
                <th>Stock Qty</th>
                <th>Last Movement</th>
                <th>Days in Stock</th>
                <th>Aging Bucket</th>
            </tr>
        </thead>
        <tbody>
        @foreach($agingData as $row)
            <tr>
                <td>{{ $row['product']->name }}</td>
                <td>{{ $row['product']->sku }}</td>
                <td>{{ $row['product']->brand->name ?? '' }}</td>
                <td>{{ $row['product']->categories->pluck('name')->join(', ') }}</td>
                <td>{{ $row['product']->stock_quantity }}</td>
                <td>{{ $row['last_movement_date'] ? $row['last_movement_date']->format('Y-m-d') : 'N/A' }}</td>
                <td>{{ $row['days_in_stock'] ?? 'N/A' }}</td>
                <td>{{ $row['aging_bucket'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<script>window.onload = function() { window.print(); };</script>
@endsection
