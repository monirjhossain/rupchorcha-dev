@extends('layouts.admin')

@section('title', 'Transactions')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Transactions</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Order</th>
                        <th>User</th>
                        <th>Gateway</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $txn)
                        <tr>
                            <td>{{ $txn->id }}</td>
                            <td>
                                @if($txn->order_id)
                                    <a href="{{ route('orders.show', $txn->order_id) }}" target="_blank">#{{ $txn->order_id }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $txn->user ? $txn->user->name : '-' }}</td>
                            <td>{{ $txn->paymentGateway ? $txn->paymentGateway->name : '-' }}</td>
                            <td>{{ $txn->amount }} {{ $txn->currency }}</td>
                            <td>
                                @if($txn->status === 'success')
                                    <span class="badge badge-success">Success</span>
                                @elseif($txn->status === 'failed')
                                    <span class="badge badge-danger">Failed</span>
                                @else
                                    <span class="badge badge-secondary">Pending</span>
                                @endif
                            </td>
                            <td>{{ $txn->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div>
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
