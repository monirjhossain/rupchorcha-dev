@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Order Details #{{ $order->id }}</h1>
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white d-flex align-items-center justify-content-between">
                    <span><i class="fas fa-box-open mr-2"></i> <strong>Order Information</strong></span>
                    <span class="badge badge-dark">#{{ $order->id }}</span>
                </div>
                <div class="card-body p-4 bg-light">
                    <div class="row align-items-center mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="mb-2">
                                <i class="fas fa-user mr-1 text-primary"></i> <strong>User:</strong> {{ $order->user ? $order->user->name : 'Guest' }}
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-envelope mr-1 text-primary"></i> <strong>Email:</strong> {{ $order->customer_email }}
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-phone mr-1 text-primary"></i> <strong>Phone:</strong> {{ $order->customer_phone }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <i class="fas fa-money-bill-wave mr-1 text-success"></i> <strong>Total:</strong> <span class="h5">{{ number_format($order->total, 2) }}</span>
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-credit-card mr-1 text-info"></i> <strong>Payment Status:</strong> <span class="badge 
                                    @if($order->payment_status === 'unpaid') badge-danger
                                    @elseif($order->payment_status === 'paid') badge-success
                                    @else badge-warning
                                    @endif
                                ">{{ ucfirst($order->payment_status) }}</span>
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-wallet mr-1 text-secondary"></i> <strong>Payment Method:</strong> {{ $order->payment_method ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                    @if($order->tracking_number)
                    <hr>
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <i class="fas fa-barcode mr-1 text-dark"></i> <strong>Tracking Number:</strong> {{ $order->tracking_number }}
                        </div>
                        <div class="col-md-6 text-md-right">
                            @if($order->courier && $order->courier->tracking_url)
                            <a href="{{ str_replace('{tracking_number}', $order->tracking_number, $order->courier->tracking_url) }}" target="_blank" class="btn btn-info btn-sm shadow-sm">
                                <i class="fas fa-truck mr-1"></i> Track Shipment
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif
                    @if($order->courier)
                    <hr>
                    <div class="bg-white rounded p-3 border">
                        <h6 class="mb-2 text-primary"><i class="fas fa-shipping-fast mr-1"></i> Courier Information</h6>
                        <div class="mb-1"><strong>Courier:</strong> {{ $order->courier->name }}</div>
                        <div><strong>Contact:</strong> {{ $order->courier->contact_number }} | {{ $order->courier->email }}</div>
                    </div>
                    @endif
                </div>
                        <!-- Status Update Card -->
                        <div class="card mb-4">
                            <div class="card-header bg-warning text-dark d-flex align-items-center">
                                <i class="fas fa-sync-alt mr-2"></i> <strong>Update Order Status</strong>
                            </div>
                            <div class="card-body bg-light">
                                <form method="POST" action="{{ route('orders.update', $order->id) }}" class="mb-0">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group mb-2">
                                        <label for="status" class="font-weight-bold">Status:</label>
                                        <div class="input-group">
                                            <select name="status" id="status" class="form-control">
                                                <option value="pending" @if($order->status === 'pending') selected @endif>Pending</option>
                                                <option value="processing" @if($order->status === 'processing') selected @endif>Processing</option>
                                                <option value="shipped" @if($order->status === 'shipped') selected @endif>Shipped</option>
                                                <option value="delivered" @if($order->status === 'delivered') selected @endif>Delivered</option>
                                                <option value="complete" @if($order->status === 'complete') selected @endif>Complete</option>
                                                <option value="cancelled" @if($order->status === 'cancelled') selected @endif>Cancelled</option>
                                            </select>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-warning btn-sm">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-list mr-2"></i> Order Items
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <i class="fas fa-history mr-2"></i> Refund / Return / Exchange History
                </div>
                <div class="card-body">
                    @if($order->refunds->count() > 0)
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Reason</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($order->refunds as $refund)
                                <tr>
                                    <td>
                                        <span class="badge badge-info">{{ ucfirst($refund->type) }}</span>
                                    </td>
                                    <td>{{ number_format($refund->amount, 2) }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($refund->status === 'pending') badge-warning
                                            @elseif($refund->status === 'approved') badge-info
                                            @elseif($refund->status === 'completed') badge-success
                                            @elseif($refund->status === 'rejected') badge-danger
                                            @endif
                                        ">
                                            {{ ucfirst($refund->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $refund->reason ?? '-' }}</td>
                                    <td>{{ $refund->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('refunds.edit', $refund->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted mb-0">No refunds / returns / exchanges yet.</p>
                    @endif
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-history mr-2"></i> Status Timeline
                </div>
                <div class="card-body">
                    @if($order->statusHistories->count() > 0)
                        <div class="timeline">
                        @foreach($order->statusHistories->reverse() as $history)
                            <div class="timeline-item mb-3">
                                <div class="d-flex">
                                    <div class="timeline-badge badge-success">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="timeline-content ml-3">
                                        <h6 class="mb-1">
                                            <span class="badge badge-primary">{{ ucfirst($history->status) }}</span>
                                            @if($history->previous_status)
                                            <small class="text-muted">from {{ ucfirst($history->previous_status) }}</small>
                                            @endif
                                        </h6>
                                        <p class="text-muted small mb-1">{{ $history->created_at->format('M d, Y H:i:s') }}</p>
                                        @if($history->changedBy)
                                        <p class="text-muted small mb-1"><strong>By:</strong> {{ $history->changedBy->name }}</p>
                                        @endif
                                        @if($history->note)
                                        <p class="text-muted small">{{ $history->note }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">No status changes recorded yet.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-shipping-fast mr-2"></i> Actions
                </div>
                <div class="card-body">
                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-edit mr-1"></i> Edit Order
                    </a>
                    @if($order->invoices->count() === 0)
                    <form action="{{ route('orders.create-invoice', $order) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-info btn-block">
                            <i class="fas fa-file-invoice-dollar mr-1"></i> Create Invoice
                        </button>
                    </form>
                    @else
                    <a href="{{ route('invoices.index') }}?search={{ $order->id }}" class="btn btn-info btn-block mb-2">
                        <i class="fas fa-file-invoice-dollar mr-1"></i> View Invoices
                    </a>
                    @endif
                    
                    @if($order->packingSlips->count() === 0)
                    <a href="{{ route('packing-slips.download', $order->id) }}" class="btn btn-danger btn-block mb-2">
                        <i class="fas fa-box mr-1"></i> Generate Packing Slip
                    </a>
                    @else
                    <a href="{{ route('packing-slips.preview', $order->id) }}" class="btn btn-danger btn-block mb-2">
                        <i class="fas fa-box mr-1"></i> View Packing Slip
                    </a>
                    @endif
                    
                    <a href="{{ route('refunds.create') }}?order_id={{ $order->id }}" class="btn btn-warning btn-block mb-2">
                        <i class="fas fa-undo mr-1"></i> Create Refund/Return
                    </a>
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Orders
                    </a>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <i class="fas fa-sticky-note mr-2"></i> Admin Notes
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <textarea name="admin_note" class="form-control" rows="5" placeholder="Add internal notes...">{{ $order->admin_note }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-save mr-1"></i> Save Notes
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <i class="fas fa-comment mr-2"></i> Customer Notes
                </div>
                <div class="card-body">
                    <p class="text-muted small">{{ $order->customer_notes ?? 'No customer notes.' }}</p>
                </div>
            </div>
            
            @if($order->invoices->count() > 0)
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-file-invoice-dollar mr-2"></i> Invoices
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                    @foreach($order->invoices as $invoice)
                        <li class="mb-2">
                            <span class="badge 
                                @if($invoice->status === 'draft') badge-secondary
                                @elseif($invoice->status === 'unpaid') badge-danger
                                @elseif($invoice->status === 'sent') badge-info
                                @elseif($invoice->status === 'paid') badge-success
                                @else badge-dark
                                @endif
                            ">{{ ucfirst($invoice->status) }}</span>
                            <strong>{{ $invoice->invoice_number }}</strong> - ৳ {{ number_format($invoice->total, 2) }}
                            <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-xs btn-primary"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('invoices.download', $invoice) }}" class="btn btn-xs btn-warning"><i class="fas fa-download"></i></a>
                        </li>
                    @endforeach
                    </ul>
                </div>
            </div>
            @endif
            
            @if($order->packingSlips->count() > 0)
            <div class="card mb-4">
                <div class="card-header bg-warning text-white">
                    <i class="fas fa-box mr-2"></i> Packing Slips
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                    @foreach($order->packingSlips as $slip)
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            <strong>{{ $slip->slip_number }}</strong>
                            <span class="text-muted">Generated: {{ $slip->generated_at->format('d M, Y H:i') }}</span>
                            <a href="{{ route('packing-slips.show', $slip) }}" class="btn btn-xs btn-primary"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('packing-slips.download', $order->id) }}" class="btn btn-xs btn-danger"><i class="fas fa-download"></i></a>
                        </li>
                    @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
