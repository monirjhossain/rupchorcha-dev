@extends('layouts.admin')
@section('title', 'Add Coupon')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 font-weight-bold text-primary"><i class="fas fa-ticket-alt mr-2"></i>Add Coupon</h1>
        <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary shadow-sm"><i class="fa fa-arrow-left"></i> Back</a>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('coupons.store') }}" method="POST">
                        @csrf
                        @include('admin.coupons.partials.form', ['coupon' => null])
                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-success px-4 shadow-sm"><i class="fa fa-check mr-1"></i> Create Coupon</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
