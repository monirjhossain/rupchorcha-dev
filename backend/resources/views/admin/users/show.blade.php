@extends('layouts.admin')
@section('title', 'Customer Details')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Customer Details</h1>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="font-weight-bold">Profile Info</h5>
                    <p><strong>Name:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Phone:</strong> {{ $user->phone }}</p>
                    <p><strong>Address:</strong> {{ $user->address }}</p>
                    <p><strong>Role:</strong> {{ ucfirst(str_replace('_', ' ', $user->role)) }}</p>
                    <p><strong>Registered:</strong> {{ $user->created_at->format('Y-m-d H:i') }}</p>
                    <hr>
                    <h5 class="font-weight-bold mt-3">Customer Analytics</h5>
                    <p><strong>Total Orders:</strong> {{ $user->orders->count() }}</p>
                    <p><strong>Total Spent:</strong> ৳{{ number_format($user->orders->sum('total'), 2) }}</p>
                    <p><strong>Last Order Date:</strong> {{ optional($user->orders->sortByDesc('created_at')->first())->created_at ? optional($user->orders->sortByDesc('created_at')->first())->created_at->format('Y-m-d H:i') : 'N/A' }}</p>
                    <hr>
                    <h5 class="font-weight-bold mt-3">Send Email/SMS</h5>
                    <form method="POST" action="{{ route('users.message', $user->id) }}">
                        @csrf
                        <div class="form-group">
                            <label for="message_type">Type</label>
                            <select name="message_type" id="message_type" class="form-control" required>
                                <option value="email">Email</option>
                                <option value="sms">SMS</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea name="message" id="message" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <h5 class="font-weight-bold">Order History</h5>
                    @if($user->orders && $user->orders->count())
                        <ul class="list-group">
                            @foreach($user->orders as $order)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Order #{{ $order->id }} (৳{{ number_format($order->total, 2) }})</span>
                                    <span class="badge badge-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'cancelled' ? 'danger' : 'success') }}">{{ ucfirst($order->status) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No orders found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
