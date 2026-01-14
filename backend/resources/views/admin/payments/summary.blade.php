@extends('layouts.admin')
@section('title', 'Payment Summary')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Payment Summary</h1>
    <form method="GET" class="mb-4">
        <div class="row align-items-end">
            <div class="col-md-4">
                <label for="from">From Date</label>
                <input type="date" name="from" id="from" class="form-control" value="{{ request('from') }}">
            </div>
            <div class="col-md-4">
                <label for="to">To Date</label>
                <input type="date" name="to" id="to" class="form-control" value="{{ request('to') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100 mt-2"><i class="fas fa-search mr-1"></i> Filter</button>
            </div>
        </div>
    </form>
    <div class="row">
        @foreach($paymentSummary as $method => $amount)
            <div class="col-md-2 mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{ $method }}</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">৳ {{ number_format($amount, 2) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-wallet fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
