@extends('layouts.admin')

@section('title', 'Create Refund/Return/Exchange Request')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Create Refund/Return/Exchange Request</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('refunds.store') }}">
                @csrf
                <div class="form-group">
                    <label for="order_id">Order ID</label>
                    <input type="number" name="order_id" id="order_id" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="user_id">User ID</label>
                    <input type="number" name="user_id" id="user_id" class="form-control">
                </div>
                <div class="form-group">
                    <label for="transaction_id">Transaction ID</label>
                    <input type="number" name="transaction_id" id="transaction_id" class="form-control">
                </div>
                <div class="form-group">
                    <label for="type">Type</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="refund">Refund</option>
                        <option value="return">Return</option>
                        <option value="exchange">Exchange</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" step="0.01" name="amount" id="amount" class="form-control">
                </div>
                <div class="form-group">
                    <label for="reason">Reason</label>
                    <input type="text" name="reason" id="reason" class="form-control">
                </div>
                <div class="form-group">
                    <label for="details">Details</label>
                    <textarea name="details" id="details" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Create Request</button>
            </form>
        </div>
    </div>
</div>
@endsection
