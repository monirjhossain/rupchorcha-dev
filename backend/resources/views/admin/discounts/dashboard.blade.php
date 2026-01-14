@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 font-weight-bold text-primary"><i class="fas fa-percent mr-2"></i>Discount Management</h1>
    </div>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title font-weight-bold">Order Discounts</h5>
                        <p class="card-text">Manage discounts applied to entire orders.</p>
                    </div>
                    <a href="{{ route('discounts.index') }}" class="btn btn-primary mt-2">Manage Order Discounts</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title font-weight-bold">Coupon Codes</h5>
                        <p class="card-text">Create and manage coupon codes for customers.</p>
                    </div>
                    <a href="{{ route('coupons.index') }}" class="btn btn-info mt-2">Manage Coupons</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title font-weight-bold">Product/Brand/Category Discount</h5>
                        <p class="card-text">Set discounts based on product, brand, or category conditions.</p>
                    </div>
                    <a href="{{ route('shipping-methods.index') }}" class="btn btn-success mt-2">Manage Condition Discounts</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
