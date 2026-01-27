@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Stock Movement History</h1>
    <form method="GET" class="mb-3">
        <div class="form-row align-items-end">
            <div class="form-group col-md-3">
                <label for="product_id">Product</label>
                <select name="product_id" id="product_id" class="form-control">
                    <option value="">All Products</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2">
                <label for="type">Type</label>
                <select name="type" id="type" class="form-control">
                    <option value="">All Types</option>
                    <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>In</option>
                    <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Out</option>
                    <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                </select>
            </div>
            <div class="form-group col-md-2">
                <label for="user_id">User</label>
                <select name="user_id" id="user_id" class="form-control">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2">
                <label for="date_from">From</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="form-control">
            </div>
            <div class="form-group col-md-2">
                <label for="date_to">To</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="form-control">
            </div>
            <div class="form-group col-md-1">
                <button type="submit" class="btn btn-info btn-block mt-4">Filter</button>
            </div>
        </div>
    </form>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Reason</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
                            <tr>
                                <td>{{ $movement->created_at }}</td>
                                <td>{{ $movement->product->name ?? '' }}</td>
                                <td>{{ ucfirst($movement->type) }}</td>
                                <td>{{ $movement->quantity }}</td>
                                <td>{{ $movement->reason }}</td>
                                <td>{{ $movement->user->name ?? '' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">No stock movements found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $movements->appends(request()->except('page'))->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
