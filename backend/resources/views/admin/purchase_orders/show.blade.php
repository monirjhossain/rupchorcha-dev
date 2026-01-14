
@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-info text-white d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0"><i class="fas fa-file-invoice"></i> Purchase Order #{{ $order->id }}</h3>
                        <span class="badge badge-pill badge-secondary px-3 py-2">{{ ucfirst($order->status) }}</span>
                    </div>
                    <div class="d-flex flex-wrap align-items-center">
                        <form method="POST" action="{{ route('purchase_orders.updateStatus', $order->id) }}" class="form-inline mr-2">
                            @csrf
                            <select name="status" class="form-control mr-2">
                                <option value="draft" {{ $order->status=='draft'?'selected':'' }}>Draft</option>
                                <option value="pending" {{ $order->status=='pending'?'selected':'' }}>Pending</option>
                                <option value="ordered" {{ $order->status=='ordered'?'selected':'' }}>Ordered</option>
                                <option value="partially_received" {{ $order->status=='partially_received'?'selected':'' }}>Partially Received</option>
                                <option value="received" {{ $order->status=='received'?'selected':'' }}>Received</option>
                                <option value="cancelled" {{ $order->status=='cancelled'?'selected':'' }}>Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Update Status</button>
                        </form>
                        <form method="POST" action="{{ route('purchase_orders.approve', $order->id) }}" class="d-inline-block mr-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                        </form>
                        <form method="POST" action="{{ route('purchase_orders.reject', $order->id) }}" class="d-inline-block mr-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                        </form>
                        <form method="POST" action="{{ route('purchase_orders.cancel', $order->id) }}" class="d-inline-block">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-warning">Cancel</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="border rounded p-3 mb-3 bg-light">
                                <h5 class="text-info"><i class="fas fa-industry"></i> Supplier Info</h5>
                                <p class="mb-1"><strong>Name:</strong> {{ $order->supplier->name }}</p>
                                <p class="mb-1"><strong>Address:</strong> {{ $order->supplier->address }}</p>
                                <p class="mb-1"><strong>Phone:</strong> {{ $order->supplier->phone }}</p>
                                <p class="mb-1"><strong>Email:</strong> {{ $order->supplier->email }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 mb-3 bg-light">
                                <h5 class="text-info"><i class="fas fa-info-circle"></i> Order Info</h5>
                                <p class="mb-1"><strong>Date:</strong> {{ $order->order_date }}</p>
                                <p class="mb-1"><strong>Warehouse:</strong> {{ $order->warehouse->name ?? '-' }}</p>
                                <p class="mb-1"><strong>Created By:</strong> {{ $order->created_by->name ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                    <h5 class="text-info mb-3"><i class="fas fa-box"></i> Items</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Subtotal</th>
                                    <th>Received</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->product->sku ?? '-' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>৳{{ number_format($item->unit_price,2) }}</td>
                                    <td>৳{{ number_format($item->total,2) }}</td>
                                    <td>{{ $item->received_quantity ?? 0 }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Total</strong></td>
                                    <td colspan="2"><strong>৳{{ number_format($order->total,2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light">
                                <h5 class="text-info"><i class="fas fa-sticky-note"></i> Notes</h5>
                                <p>{{ $order->notes }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light">
                                <h5 class="text-info"><i class="fas fa-paperclip"></i> Attachments</h5>
                                @if($order->attachment)
                                    <a href="{{ asset('storage/' . $order->attachment) }}" target="_blank" class="btn btn-outline-info btn-sm">View Attachment</a>
                                @else
                                    <span class="text-muted">No attachment</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light">
                                <h5 class="text-info"><i class="fas fa-history"></i> Status History</h5>
                                <ul class="list-group mb-3">
                                    @foreach($order->status_history ?? [] as $status)
                                        <li class="list-group-item">
                                            <strong>{{ ucfirst($status->status) }}</strong> by {{ $status->user->name ?? 'System' }} on {{ $status->created_at }}
                                            @if($status->notes)
                                                <br><span class="text-muted">{{ $status->notes }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light">
                                <h5 class="text-info"><i class="fas fa-user-shield"></i> Audit Trail</h5>
                                <ul class="list-group">
                                    <li class="list-group-item">Created by: {{ $order->created_by->name ?? '-' }}</li>
                                    <li class="list-group-item">Last updated by: {{ $order->updated_by->name ?? '-' }}</li>
                                    <li class="list-group-item">Approved by: {{ $order->approved_by->name ?? '-' }}</li>
                                    <li class="list-group-item">Received by: {{ $order->received_by->name ?? '-' }}</li>
                                    <li class="list-group-item">Cancelled by: {{ $order->cancelled_by->name ?? '-' }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3 text-center">
                <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to List</a>
            </div>
        </div>
    </div>
</div>
@endsection
