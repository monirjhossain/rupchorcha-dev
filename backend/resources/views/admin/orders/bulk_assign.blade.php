@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Bulk Assign Courier</h1>
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('orders.bulkAssignCourier') }}">
                @csrf
                <div class="form-group">
                    <label for="courier_id">Select Courier</label>
                    <select name="courier_id" id="courier_id" class="form-control" required>
                        @foreach($couriers as $courier)
                            <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="order_ids">Select Orders</label>
                    <select name="order_ids[]" id="order_ids" class="form-control" multiple required>
                        @foreach($orders as $order)
                            <option value="{{ $order->id }}">Order #{{ $order->id }} - {{ $order->user ? $order->user->name : 'Guest' }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Assign Courier</button>
            </form>
        </div>
    </div>
</div>
@endsection
