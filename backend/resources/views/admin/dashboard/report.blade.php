@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Dashboard Report</h1>
    <div class="mb-3">
        <a href="{{ route('dashboard.report.export') }}" class="btn btn-success">Export CSV</a>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h4>Brand Wise Sales Report</h4>
            <table class="table table-bordered">
                <thead><tr><th>Brand</th><th>Sales</th></tr></thead>
                <tbody>
                @foreach($brandSales as $row)
                    <tr><td>{{ $row['brand'] }}</td><td>{{ $row['sales'] }}</td></tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <h4>Category Wise Sales Report</h4>
            <table class="table table-bordered">
                <thead><tr><th>Category</th><th>Sales</th></tr></thead>
                <tbody>
                @foreach($categorySales as $row)
                    <tr><td>{{ $row['category'] }}</td><td>{{ $row['sales'] }}</td></tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <h4>Total Sales Report</h4>
            <div class="card card-body">
                <h5>Total Sales: {{ $totalSales }}</h5>
            </div>
        </div>
        <div class="col-md-6">
            <h4>Maximum Product Selling List</h4>
            <table class="table table-bordered">
                <thead><tr><th>Product</th><th>Orders</th></tr></thead>
                <tbody>
                @foreach($maxProducts as $product)
                    <tr><td>{{ $product->name }}</td><td>{{ $product->orders_count }}</td></tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
