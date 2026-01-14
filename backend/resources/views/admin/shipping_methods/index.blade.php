@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Shipping Methods</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Manage Shipping Methods</h6>
        </div>
        <div class="card-body">
            <a href="{{ route('shipping-methods.create') }}" class="btn btn-success mb-3">Add New Shipping Method</a>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Cost</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shippingMethods as $method)
                        <tr>
                            <td>{{ $method->name }}</td>
                            <td>{{ ucfirst($method->type) }}</td>
                            <td>৳{{ number_format($method->cost, 2) }}</td>
                            <td>
                                @if($method->status)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('shipping-methods.edit', $method) }}" class="btn btn-sm btn-info">Edit</a>
                                <form action="{{ route('shipping-methods.destroy', $method) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this shipping method?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No shipping methods found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
