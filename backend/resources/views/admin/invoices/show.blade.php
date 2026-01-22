@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 text-gray-800">Invoice {{ $invoice->invoice_number }}</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back
            </a>
            <a href="{{ route('invoices.download', $invoice) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-download mr-1"></i> Order Invoice
            </a>
            <a href="{{ route('packing-slips.download', $invoice->order->id) }}" class="btn btn-danger btn-sm">
                <i class="fas fa-box mr-1"></i> Packing Slip
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-file-invoice-dollar mr-2"></i> Invoice Details</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted">Invoice Information</h6>
                            <p class="mb-1"><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</p>
                            <p class="mb-1"><strong>Order #:</strong> <a href="{{ route('orders.show', $invoice->order) }}">#{{ $invoice->order->id }}</a></p>
                            <p class="mb-1"><strong>Issued Date:</strong> {{ $invoice->issued_at->format('M d, Y') }}</p>
                            <p class="mb-1"><strong>Due Date:</strong> {{ $invoice->due_at?->format('M d, Y') ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Payment Date:</strong> {{ $invoice->paid_at?->format('M d, Y') ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted">Customer Information</h6>
                            <p class="mb-1"><strong>Name:</strong> {{ $invoice->meta['customer_name'] ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $invoice->meta['customer_email'] ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Phone:</strong> {{ $invoice->meta['customer_phone'] ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Address:</strong> {{ substr($invoice->meta['shipping_address'] ?? 'N/A', 0, 50) }}...</p>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-3">Invoice Items</h6>
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>Description</th>
                                <th width="80">Qty</th>
                                <th width="100">Unit Price</th>
                                <th width="100">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td class="text-right">{{ $item->quantity }}</td>
                                <td class="text-right">৳ {{ number_format($item->price, 2) }}</td>
                                <td class="text-right"><strong>৳ {{ number_format($item->price * $item->quantity, 2) }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row justify-content-end mt-4">
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Subtotal:</span>
                                        <strong>৳ {{ number_format($invoice->amount, 2) }}</strong>
                                    </div>
                                    
                                    @if($invoice->discount > 0)
                                    <div class="d-flex justify-content-between mb-2 text-danger">
                                        <span class="text-muted">Discount:</span>
                                        <strong>- ৳ {{ number_format($invoice->discount, 2) }}</strong>
                                    </div>
                                    @endif
                                    
                                    @if($invoice->order->shipping_cost > 0)
                                    <div class="d-flex justify-content-between mb-3 text-info">
                                        <span class="text-muted">Shipping Cost:</span>
                                        <strong>+ ৳ {{ number_format($invoice->order->shipping_cost, 2) }}</strong>
                                    </div>
                                    @endif
                                    
                                    <hr class="my-2">
                                    
                                    <div class="d-flex justify-content-between" style="font-size: 18px;">
                                        <strong>Total Due:</strong>
                                        <strong class="text-primary">৳ {{ number_format($invoice->total, 2) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="fas fa-cogs mr-2"></i> Actions</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="badge badge-lg 
                            @if($invoice->status === 'draft') badge-secondary
                            @elseif($invoice->status === 'unpaid') badge-danger
                            @elseif($invoice->status === 'sent') badge-info
                            @elseif($invoice->status === 'paid') badge-success
                            @else badge-dark
                            @endif
                        ">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </div>

                    @if($invoice->status !== 'paid')
                    <form action="{{ route('invoices.mark-as-paid', $invoice) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block btn-sm">
                            <i class="fas fa-check-circle mr-1"></i> Mark as Paid
                        </button>
                    </form>
                    @else
                    <form action="{{ route('invoices.mark-as-unpaid', $invoice) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-block btn-sm">
                            <i class="fas fa-undo mr-1"></i> Mark as Unpaid
                        </button>
                    </form>
                    @endif

                    <form action="{{ route('invoices.send', $invoice) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-info btn-block btn-sm">
                            <i class="fas fa-envelope mr-1"></i> Send via Email
                        </button>
                    </form>

                    <a href="{{ route('invoices.download', $invoice) }}" class="btn btn-primary btn-block btn-sm mb-2">
                        <i class="fas fa-download mr-1"></i> Download PDF
                    </a>

                    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block btn-sm" onclick="return confirm('Delete this invoice?')">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>

            @if($invoice->paid_at)
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-check-double mr-2"></i> Payment Info</h6>
                </div>
                <div class="card-body small">
                    <p class="mb-1"><strong>Paid Date:</strong> {{ $invoice->paid_at->format('M d, Y H:i') }}</p>
                    <p class="mb-0"><strong>Amount Paid:</strong> ৳ {{ number_format($invoice->total, 2) }}</p>
                </div>
            </div>
            @endif

            @if($invoice->notes)
            <div class="card shadow mb-4">
                <div class="card-header bg-warning text-white">
                    <h6 class="mb-0"><i class="fas fa-sticky-note mr-2"></i> Invoice Notes</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">{{ $invoice->notes }}</p>
                </div>
            </div>
            @endif

            @if($invoice->order->refunds->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="fas fa-undo mr-2"></i> Associated Refunds</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                    @foreach($invoice->order->refunds as $refund)
                        <li class="mb-2">
                            <span class="badge badge-danger">{{ ucfirst($refund->status) }}</span>
                            <strong>৳ {{ number_format($refund->amount, 2) }}</strong>
                            <small class="text-muted">{{ $refund->created_at->format('M d, Y') }}</small>
                        </li>
                    @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i> Status Info</h6>
                </div>
                <div class="card-body small text-muted">
                    <ul class="list-unstyled">
                        <li class="mb-2"><strong>Draft:</strong> Invoice created but not finalized</li>
                        <li class="mb-2"><strong>Unpaid:</strong> Invoice issued, waiting for payment</li>
                        <li class="mb-2"><strong>Sent:</strong> Invoice sent to customer</li>
                        <li class="mb-2"><strong>Paid:</strong> Payment received</li>
                        <li><strong>Cancelled:</strong> Invoice voided</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
