@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Maximum Product Selling List</h1>
    <a href="{{ route('report.max_products.export') }}" class="btn btn-success mb-3">Export CSV</a>
    <table class="table table-bordered">
        <thead><tr><th>Product</th><th>Orders</th></tr></thead>
        <tbody>
        @foreach($maxProducts as $product)
            <tr><td>{{ $product->name }}</td><td>{{ $product->orders_count }}</td></tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
