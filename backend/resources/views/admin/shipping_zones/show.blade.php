@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 font-weight-bold text-primary"><i class="fas fa-globe-asia mr-2"></i>{{ $zone->name }} Zone</h1>
        <a href="{{ route('shipping-zones.index') }}" class="btn btn-secondary shadow-sm"><i class="fa fa-arrow-left"></i> Back to Zones</a>
    </div>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="font-weight-bold mb-3">Zone Details</h5>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Country:</strong> {{ $zone->country }}</div>
                <div class="col-md-4"><strong>Region:</strong> {{ $zone->region ?? '-' }}</div>
                <div class="col-md-4"><strong>Postcode:</strong> {{ $zone->postcode ?? '-' }}</div>
            </div>
        </div>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="font-weight-bold mb-0">Shipping Methods</h5>
            </div>

            <!-- Inline Add Shipping Method Form -->
            <form action="{{ route('shipping-zones.methods.store', $zone) }}" method="POST" class="mb-4 border rounded p-3 bg-light">
                @csrf
                <div class="form-row align-items-end">
                    <div class="form-group col-md-3 mb-0">
                        <label for="name" class="mb-0">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group col-md-2 mb-0">
                        <label for="type" class="mb-0">Type</label>
                        <select name="type" class="form-control" required>
                            <option value="flat">Flat Rate</option>
                            <option value="free">Free Delivery</option>
                            <option value="pickup">Local Pickup</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2 mb-0">
                        <label for="cost" class="mb-0">Cost</label>
                        <input type="number" step="0.01" name="cost" class="form-control" required>
                    </div>
                    <div class="form-group col-md-1 mb-0">
                        <label for="sort_order" class="mb-0">Sort</label>
                        <input type="number" name="sort_order" class="form-control" value="0">
                    </div>
                    <div class="form-group col-md-2 mb-0">
                        <label for="status" class="mb-0">Status</label>
                        <select name="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2 mb-0">
                        <button type="submit" class="btn btn-primary btn-block">Add Method</button>
                    </div>
                </div>
            </form>
            <!-- End Add Shipping Method Form -->

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Cost</th>
                            <th>Status</th>
                            <th>Sort</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($zone->shippingMethods as $method)
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
                            <td>{{ $method->sort_order }}</td>
                            <td>
                                <a href="{{ route('shipping-methods.edit', $method) }}" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                                <form action="{{ route('shipping-methods.destroy', $method) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this shipping method?')"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No shipping methods found for this zone.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
