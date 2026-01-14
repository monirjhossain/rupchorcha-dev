@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Total Sales Report</h1>
    <a href="{{ route('report.total.export') }}" class="btn btn-success mb-3">Export CSV</a>
    <div class="card card-body">
        <h5>Total Sales: {{ $totalSales }}</h5>
    </div>
</div>
@endsection
