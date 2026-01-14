@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 font-weight-bold text-primary"><i class="fas fa-shipping-fast mr-2"></i>{{ $shippingMethod->name }} <span class="badge badge-secondary">Details</span></h1>
        <a href="{{ route('shipping-methods.index') }}" class="btn btn-secondary shadow-sm"><i class="fa fa-arrow-left"></i> Back to Methods</a>
    </div>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="font-weight-bold mb-3">Method Info</h5>
            <div class="row mb-3">
                <div class="col-md-3"><strong>Type:</strong> {{ ucfirst($shippingMethod->type) }}</div>
                <div class="col-md-3"><strong>Cost:</strong> ৳{{ number_format($shippingMethod->cost, 2) }}</div>
                <div class="col-md-3"><strong>Status:</strong> @if($shippingMethod->status)<span class="badge badge-success">Active</span>@else<span class="badge badge-secondary">Inactive</span>@endif</div>
                <div class="col-md-3"><strong>Sort Order:</strong> {{ $shippingMethod->sort_order }}</div>
            </div>
        </div>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="font-weight-bold mb-3">Shipping Conditions</h5>
            <a href="{{ route('shipping-methods.conditions', $shippingMethod) }}" class="btn btn-info mb-3"><i class="fa fa-plus"></i> Manage Conditions</a>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Type</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shippingMethod->conditions as $condition)
                        <tr>
                            <td><span class="badge badge-info text-uppercase">{{ $condition->condition_type }}</span></td>
                            <td>
                                @if($condition->condition_type === 'brand')
                                    {{ \App\Models\Brand::find($condition->condition_value)?->name ?? $condition->condition_value }}
                                @elseif($condition->condition_type === 'product')
                                    {{ \App\Models\Product::find($condition->condition_value)?->name ?? $condition->condition_value }}
                                @elseif($condition->condition_type === 'category')
                                    {{ \App\Models\Category::find($condition->condition_value)?->name ?? $condition->condition_value }}
                                @else
                                    {{ $condition->condition_value }}
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center">No conditions set for this method.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
