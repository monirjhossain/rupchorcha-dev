@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    📦 Packing Slip
                    <span class="badge badge-warning">{{ $packingSlip->slip_number }}</span>
                </h2>
                <a href="{{ route('packing-slips.download', $order->id) }}" class="btn btn-danger">
                    <i class="fas fa-download"></i> Download PDF
                </a>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Shipping Address -->
        <div class="col-md-6 mb-4">
            <div class="card border-warning">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">📍 Ship To Address</h5>
                </div>
                <div class="card-body">
                    <p><strong class="text-dark">{{ $packingSlip->meta['customer_name'] ?? ($order->customer_name ?? 'N/A') }}</strong></p>
                    <p>📞 {{ $packingSlip->meta['customer_phone'] ?? ($order->customer_phone ?? 'N/A') }}</p>
                    <p>📧 {{ $packingSlip->meta['customer_email'] ?? ($order->customer_email ?? 'N/A') }}</p>
                    <hr>
                    <p style="line-height: 1.8; color: #666; word-break: break-word;">{{ $packingSlip->meta['shipping_address'] ?? ($order->shipping_address ?? 'N/A') }}</p>
                </div>
            </div>
        </div>

        <!-- Slip Details -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">📋 Slip Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <p><strong>Slip #:</strong> <span class="badge badge-secondary">{{ $packingSlip->slip_number }}</span></p>
                            <p><strong>Order #:</strong> <a href="{{ route('orders.show', $order->id) }}" class="text-primary">#{{ $order->id }}</a></p>
                            <p><strong>Generated:</strong> {{ $packingSlip->generated_at->format('d M, Y H:i') }}</p>
                        </div>
                        <div class="col-6">
                            <p><strong>Courier:</strong> <strong class="text-success">{{ $order->courier?->name ?? 'Not Assigned' }}</strong></p>
                            <p><strong>Tracking #:</strong> {{ $order->tracking_number ?? '—' }}</p>
                            <p><strong>Printed:</strong> @if($packingSlip->printed_at) <span class="badge badge-success">{{ $packingSlip->printed_at->format('d M, Y H:i') }}</span> @else <span class="text-muted">Not yet</span> @endif</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Items to Pack -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">✓ Items to Pack ({{ $order->items->count() }})</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 50%;">Product Name</th>
                                <th style="width: 20%; text-align: center;">Quantity</th>
                                <th style="width: 15%; text-align: center;">Unit Price</th>
                                <th style="width: 15%; text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->product_name }}</strong>
                                    @if($item->variant)
                                    <br><small class="text-muted">{{ $item->variant }}</small>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge badge-primary">{{ $item->quantity }}</span>
                                </td>
                                <td style="text-align: center;">৳ {{ number_format($item->price, 2) }}</td>
                                <td style="text-align: right;">৳ {{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No items found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-0">
                                <strong>Total Items:</strong> <span class="badge badge-info">{{ $packingSlip->meta['item_count'] ?? $order->items->count() }}</span>
                            </p>
                        </div>
                        <div class="col-md-6 text-right">
                            <p class="mb-0">
                                <strong>Total Quantity:</strong> <span class="badge badge-primary">{{ $packingSlip->meta['total_quantity'] ?? $order->items->sum('quantity') }} units</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Special Notes -->
    @if($packingSlip->meta['notes'])
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-warning">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">⚠️ Special Notes</h5>
                </div>
                <div class="card-body">
                    <p class="text-danger">{{ $packingSlip->meta['notes'] }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Order Summary -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📊 Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <p><strong>Order Date:</strong></p>
                            <p class="text-success">{{ $order->created_at->format('d M, Y H:i') }}</p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Order Status:</strong></p>
                            <p><span class="badge 
                                @if($order->status === 'pending') badge-warning
                                @elseif($order->status === 'processing') badge-info
                                @elseif($order->status === 'shipped') badge-primary
                                @elseif($order->status === 'delivered') badge-success
                                @elseif($order->status === 'cancelled') badge-danger
                                @else badge-secondary
                                @endif
                            ">{{ ucfirst($order->status) }}</span></p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Payment Status:</strong></p>
                            <p><span class="badge 
                                @if($order->payment_status === 'unpaid') badge-danger
                                @elseif($order->payment_status === 'paid') badge-success
                                @else badge-warning
                                @endif
                            ">{{ ucfirst($order->payment_status) }}</span></p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Order Total:</strong></p>
                            <p class="text-primary"><strong>৳ {{ number_format($order->total, 2) }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row">
        <div class="col-12 mb-4">
            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Order
            </a>
            <a href="{{ route('packing-slips.download', $order->id) }}" class="btn btn-danger">
                <i class="fas fa-download"></i> Download PDF
            </a>
            <a href="{{ route('packing-slips.download', $order->id) }}" class="btn btn-primary" onclick="window.print(); return false;">
                <i class="fas fa-print"></i> Print
            </a>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn, .card-header, .alert, .d-flex { display: none; }
        .container-fluid { padding: 0; margin: 0; }
        .card { border: none; box-shadow: none; }
        .table { font-size: 12px; }
    }
</style>
@endsection
