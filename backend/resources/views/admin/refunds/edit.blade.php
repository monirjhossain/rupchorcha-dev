@extends('layouts.admin')

@section('title', 'Edit Refund/Return/Exchange Request')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Refund/Return/Exchange Request</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('refunds.update', $refund->id) }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="order_id">Order ID</label>
                    <input type="number" name="order_id" id="order_id" class="form-control" value="{{ $refund->order_id }}" required>
                </div>
                <div class="form-group">
                    <label for="user_id">User ID</label>
                    <input type="number" name="user_id" id="user_id" class="form-control" value="{{ $refund->user_id }}">
                </div>
                <div class="form-group">
                    <label for="transaction_id">Transaction ID</label>
                    <input type="number" name="transaction_id" id="transaction_id" class="form-control" value="{{ $refund->transaction_id }}">
                </div>
                <div class="form-group">
                    <label for="type">Type</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="refund" {{ $refund->type == 'refund' ? 'selected' : '' }}>Refund</option>
                        <option value="return" {{ $refund->type == 'return' ? 'selected' : '' }}>Return</option>
                        <option value="exchange" {{ $refund->type == 'exchange' ? 'selected' : '' }}>Exchange</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" step="0.01" name="amount" id="amount" class="form-control" value="{{ $refund->amount }}">
                </div>
                <div class="form-group">
                    <label for="reason">Reason</label>
                    <input type="text" name="reason" id="reason" class="form-control" value="{{ $refund->reason }}">
                </div>
                <div class="form-group">
                    <label for="details">Details</label>
                    <textarea name="details" id="details" class="form-control">{{ $refund->details }}</textarea>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="pending" {{ $refund->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $refund->status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $refund->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="completed" {{ $refund->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Request</button>
            </form>
        </div>
    </div>
</div>
@endsection
