@extends('layouts.admin')

@section('title', 'Refund/Return/Exchange Request Details')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Request Details</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">ID</dt>
                <dd class="col-sm-9">{{ $refund->id }}</dd>
                <dt class="col-sm-3">Order</dt>
                <dd class="col-sm-9">
                    @if($refund->order_id)
                        <a href="{{ route('orders.show', $refund->order_id) }}" target="_blank">#{{ $refund->order_id }}</a>
                    @else
                        -
                    @endif
                </dd>
                <dt class="col-sm-3">User</dt>
                <dd class="col-sm-9">{{ $refund->user ? $refund->user->name : '-' }}</dd>
                <dt class="col-sm-3">Type</dt>
                <dd class="col-sm-9">{{ ucfirst($refund->type) }}</dd>
                <dt class="col-sm-3">Amount</dt>
                <dd class="col-sm-9">{{ $refund->amount ?? '-' }}</dd>
                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">{{ ucfirst($refund->status) }}</dd>
                <dt class="col-sm-3">Reason</dt>
                <dd class="col-sm-9">{{ $refund->reason ?? '-' }}</dd>
                <dt class="col-sm-3">Details</dt>
                <dd class="col-sm-9">{{ $refund->details ?? '-' }}</dd>
                <dt class="col-sm-3">Created At</dt>
                <dd class="col-sm-9">{{ $refund->created_at->format('Y-m-d H:i') }}</dd>
            </dl>
            <div class="mt-3">
                <a href="{{ route('refunds.edit', $refund->id) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('refunds.destroy', $refund->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
