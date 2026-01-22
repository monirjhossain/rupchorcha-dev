@extends('layouts.admin')

@section('title', 'Create Refund/Return/Exchange Request')

@section('content')
@php
    $orderId = request('order_id');
@endphp

<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Create Refund / Return / Exchange</h1>
            <p class="text-muted mb-0">Log customer issue, choose resolution type, and track status.</p>
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back to Orders
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form method="POST" action="{{ route('refunds.store') }}">
                        @csrf

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="order_id" class="font-weight-semibold">Order ID <span class="text-danger">*</span></label>
                                <input type="number" name="order_id" id="order_id" class="form-control" value="{{ old('order_id', $orderId) }}" placeholder="e.g. 1024" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="user_id" class="font-weight-semibold">User ID</label>
                                <input type="number" name="user_id" id="user_id" class="form-control" value="{{ old('user_id') }}" placeholder="Optional">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="transaction_id" class="font-weight-semibold">Transaction ID</label>
                                <input type="text" name="transaction_id" id="transaction_id" class="form-control" value="{{ old('transaction_id') }}" placeholder="Payment reference (if any)">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="type" class="font-weight-semibold">Request Type <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="refund" {{ old('type') === 'refund' ? 'selected' : '' }}>Refund</option>
                                    <option value="return" {{ old('type') === 'return' ? 'selected' : '' }}>Return</option>
                                    <option value="exchange" {{ old('type') === 'exchange' ? 'selected' : '' }}>Exchange</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="amount" class="font-weight-semibold">Amount</label>
                                <input type="number" step="0.01" name="amount" id="amount" class="form-control" value="{{ old('amount') }}" placeholder="e.g. 1250.00">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="status" class="font-weight-semibold">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ old('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ old('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="reason" class="font-weight-semibold">Reason</label>
                            <input type="text" name="reason" id="reason" class="form-control" value="{{ old('reason') }}" placeholder="Short title (e.g. Damaged item)">
                        </div>

                        <div class="form-group">
                            <label for="details" class="font-weight-semibold">Details</label>
                            <textarea name="details" id="details" class="form-control" rows="4" placeholder="Describe the issue, photos, courier info, etc.">{{ old('details') }}</textarea>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-4">
                            <div class="text-muted small">Fields marked * are required</div>
                            <button type="submit" class="btn btn-primary px-4">Create Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-3">Guidelines</h6>
                    <ul class="list-unstyled mb-0 small text-muted">
                        <li class="mb-2">Attach payment reference when refunding to original method.</li>
                        <li class="mb-2">For exchanges, include desired variant in details.</li>
                        <li class="mb-2">Use “Approved” only after QC verification.</li>
                        <li class="mb-2">Set status “Completed” after refund is actually processed.</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-3">Quick Tips</h6>
                    <div class="d-flex align-items-start mb-3">
                        <span class="badge badge-soft-primary mr-3">1</span>
                        <div class="small">Verify order ID and amount against payment records.</div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <span class="badge badge-soft-primary mr-3">2</span>
                        <div class="small">Capture customer consent for refunds to store credit.</div>
                    </div>
                    <div class="d-flex align-items-start">
                        <span class="badge badge-soft-primary mr-3">3</span>
                        <div class="small">If exchange, check inventory before approval.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
