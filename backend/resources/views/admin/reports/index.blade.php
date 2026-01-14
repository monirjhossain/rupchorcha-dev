@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Reports & Analytics</h1>
    <div class="row">
        <div class="col-md-6 col-lg-3 mb-4">
            <a href="{{ route('reports.sales') }}" class="card shadow h-100 py-2 text-decoration-none">
                <div class="card-body">
                    <h5 class="card-title">Sales Analytics</h5>
                    <p class="card-text">View sales trends and export data.</p>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <a href="{{ route('reports.revenue') }}" class="card shadow h-100 py-2 text-decoration-none">
                <div class="card-body">
                    <h5 class="card-title">Revenue Analytics</h5>
                    <p class="card-text">Analyze revenue and export reports.</p>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <a href="{{ route('reports.top_products') }}" class="card shadow h-100 py-2 text-decoration-none">
                <div class="card-body">
                    <h5 class="card-title">Top Products</h5>
                    <p class="card-text">See best-selling products and export lists.</p>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <a href="{{ route('reports.customers') }}" class="card shadow h-100 py-2 text-decoration-none">
                <div class="card-body">
                    <h5 class="card-title">Customer Analytics</h5>
                    <p class="card-text">Customer insights and export options.</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
