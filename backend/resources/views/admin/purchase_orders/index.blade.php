@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><i class="fas fa-file-invoice"></i> Purchase Orders</h1>
    <a href="{{ route('purchase_orders.create') }}" class="btn btn-success mb-3"><i class="fas fa-plus"></i> New Purchase Order</a>
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="" class="form-inline mb-2 flex-wrap">
                <input type="text" name="q" class="form-control mr-2 mb-2" placeholder="Search by PO # or supplier" value="{{ request('q') }}">
                <select name="supplier_id" class="form-control mr-2 mb-2">
                    <option value="">All Suppliers</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-control mr-2 mb-2">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                    <option value="ordered" {{ request('status')=='ordered'?'selected':'' }}>Ordered</option>
                    <option value="received" {{ request('status')=='received'?'selected':'' }}>Received</option>
                    <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Cancelled</option>
                </select>
                <input type="date" name="date_from" class="form-control mr-2 mb-2" value="{{ request('date_from') }}" placeholder="From">
                <input type="date" name="date_to" class="form-control mr-2 mb-2" value="{{ request('date_to') }}" placeholder="To">
                <button type="submit" class="btn btn-primary mb-2">Search</button>
            </form>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Supplier</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->supplier->name ?? '-' }}</td>
                        <td>{{ $order->order_date }}</td>
                        <td><span class="badge badge-{{ $order->status == 'received' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'secondary') }}">{{ ucfirst($order->status) }}</span></td>
                        <td>৳{{ number_format($order->total,2) }}</td>
                        <td>
                                                        <!-- Preview PDF Modal Trigger -->
                                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#poPreviewModal{{ $order->id }}" title="Preview PDF"><i class="fas fa-eye"></i></button>
                                                        <a href="{{ route('purchase_orders.showDetails', $order->id) }}" class="btn btn-sm btn-warning" title="View Details"><i class="fas fa-info-circle"></i></a>

                                                        <a href="{{ route('purchase_orders.edit', $order->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                                        <a href="{{ route('purchase_orders.pdf', $order->id) }}" class="btn btn-sm btn-secondary" title="Download PDF"><i class="fas fa-file-pdf"></i></a>

                                                        <!-- Modal for PDF Preview -->
                                                        <div class="modal fade" id="poPreviewModal{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="poPreviewModalLabel{{ $order->id }}" aria-hidden="true">
                                                            <div class="modal-dialog modal-xl" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="poPreviewModalLabel{{ $order->id }}">Purchase Order #{{ $order->id }} Preview</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body" style="height:80vh;">
                                                                        <iframe src="{{ route('purchase_orders.preview', $order->id) }}" style="width:100%;height:100%;border:none;"></iframe>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <a href="{{ route('purchase_orders.pdf', $order->id) }}" class="btn btn-success" target="_blank"><i class="fas fa-download"></i> Download PDF</a>
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">No purchase orders found.</td></tr>
                @endforelse
                </tbody>
            </table>
            <div>
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
