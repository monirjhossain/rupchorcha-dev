@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Brand Wise Sales Report</h1>
    <a href="{{ route('report.brand.export') }}" class="btn btn-success mb-3">Export CSV</a>
    <table class="table table-bordered">
        <thead><tr><th>Brand</th><th>Sales</th></tr></thead>
        <tbody>
        @foreach($brandSales as $row)
            <tr><td>{{ $row['brand'] }}</td><td>{{ $row['sales'] }}</td></tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
