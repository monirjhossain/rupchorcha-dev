@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Order Management</h1>
    <div class="row mb-4">
        <!-- Payment Method Summary Cards -->
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orders->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">৳ {{ number_format($orders->sum('total'), 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-coins fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pending Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orders->where('status', 'pending')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Cancelled Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orders->where('status', 'cancelled')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Order Search & Filter -->
    <form method="GET" action="" class="mb-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="mb-3"><i class="fas fa-filter mr-2"></i> Advanced Filters</h6>
                <div class="row">
                    <div class="col-md-2">
                        <label for="search">Search</label>
                        <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Order ID, User name...">
                    </div>
                    <div class="col-md-2">
                        <label for="status">Order Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">All</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="payment_status">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="form-control">
                            <option value="">All</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="courier_id">Courier</label>
                        <select name="courier_id" id="courier_id" class="form-control">
                            <option value="">All</option>
                            @foreach(\App\Models\Courier::orderBy('name')->get() as $courier)
                            <option value="{{ $courier->id }}" {{ request('courier_id') == $courier->id ? 'selected' : '' }}>{{ $courier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date_from">Date From</label>
                        <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to">Date To</label>
                        <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search mr-1"></i> Filter</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-block"><i class="fas fa-redo mr-1"></i> Reset</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Order List</h6>
            <a href="{{ route('orders.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Create Order
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Created At</th>
                                <th>Courier</th>
                                <th>Tracking</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td><strong>#{{ $order->id }}</strong></td>
                            <td>
                                <span class="text-dark">{{ $order->customer_name ?? 'N/A' }}</span>
                                <br>
                                <small class="text-muted">{{ $order->customer_email ?? 'N/A' }}</small>
                            </td>
                            <td>
                                @if($order->user)
                                    {{ $order->user->name }}
                                @else
                                    <span class="badge badge-secondary">Guest</span>
                                @endif
                            </td>
                            <td><span class="badge badge-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'cancelled' ? 'danger' : 'success') }}">{{ ucfirst($order->status) }}</span></td>
                            <td><strong>৳ {{ number_format($order->total, 2) }}</strong></td>
                            <td><span class="badge badge-{{ $order->payment_status == 'paid' ? 'success' : 'secondary' }}">{{ ucfirst($order->payment_status) }}</span></td>
                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    {{ $order->courier ? $order->courier->name : 'N/A' }}
                                </td>
                                <td>
                                    @if($order->courier && $order->courier->tracking_url && $order->tracking_number)
                                        <a href="{{ str_replace('{tracking_number}', $order->tracking_number, $order->courier->tracking_url) }}" target="_blank">Track</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            <td style="padding: 10px;">
                                <div class="btn-group" role="group" style="gap: 5px; display: flex; flex-wrap: wrap;">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm" title="View Order"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-primary btn-sm" title="Edit Order"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete Order" onclick="return confirm('Delete this order?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                    <form action="{{ route('orders.update', $order->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="send_to_courier" value="1">
                                        <button type="submit" class="btn btn-warning btn-sm" title="Send to Courier" onclick="return confirm('Send this order to courier?')"><i class="fas fa-truck"></i></button>
                                    </form>
                                    <a href="{{ route('refunds.create') }}?order_id={{ $order->id }}" class="btn btn-secondary btn-sm" title="Refund/Return/Exchange"><i class="fas fa-undo"></i></a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center">No orders found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
