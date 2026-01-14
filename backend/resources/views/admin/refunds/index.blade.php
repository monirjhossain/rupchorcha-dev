@extends('layouts.admin')

@section('title', 'Refund/Return/Exchange Requests')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Refund/Return/Exchange Requests</h1>
    <div class="mb-3">
        <a href="{{ route('refunds.create') }}" class="btn btn-primary">Create Request</a>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Order</th>
                        <th>User</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($refunds as $refund)
                        <tr>
                            <td>{{ $refund->id }}</td>
                            <td>
                                @if($refund->order_id)
                                    <a href="{{ route('orders.show', $refund->order_id) }}" target="_blank">#{{ $refund->order_id }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $refund->user ? $refund->user->name : '-' }}</td>
                            <td>{{ ucfirst($refund->type) }}</td>
                            <td>{{ $refund->amount ?? '-' }}</td>
                            <td>
                                @if($refund->status === 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @elseif($refund->status === 'rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @elseif($refund->status === 'completed')
                                    <span class="badge badge-primary">Completed</span>
                                @else
                                    <span class="badge badge-secondary">Pending</span>
                                @endif
                            </td>
                            <td>{{ $refund->created_at->format('Y-m-d H:i') }}</td>
                            <td><a href="{{ route('refunds.show', $refund->id) }}" class="btn btn-sm btn-info">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div>
                {{ $refunds->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
