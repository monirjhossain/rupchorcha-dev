@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Category Wise Sales Report</h1>
    <a href="{{ route('report.category.export') }}" class="btn btn-success mb-3">Export CSV</a>
    <table class="table table-bordered">
        <thead><tr><th>Category</th><th>Sales</th></tr></thead>
        <tbody>
        @foreach($categorySales as $row)
            <tr><td>{{ $row['category'] }}</td><td>{{ $row['sales'] }}</td></tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
