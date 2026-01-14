@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><i class="fas fa-edit"></i> Edit Purchase Order</h1>
    <form action="{{ route('purchase_orders.update', $order->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">Purchase Order Details</div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Supplier <span class="text-danger">*</span></label>
                        <select name="supplier_id" class="form-control" required>
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ $order->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Date <span class="text-danger">*</span></label>
                        <input type="date" name="order_date" class="form-control" value="{{ $order->order_date }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes">{{ $order->notes }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-info text-white">Products</div>
            <div class="card-body bg-light">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover bg-white" id="edit-products-table">
                        <thead class="thead-light">
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <select name="product_id[]" class="form-control product-select" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-cost-price="{{ $product->cost_price ?? 0 }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="quantity[]" class="form-control" min="1" value="{{ $item->quantity }}" required></td>
                                <td><input type="number" name="unit_price[]" class="form-control" min="0" step="0.01" value="{{ $item->unit_price }}" required></td>
                                <td class="row-subtotal">{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                                <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-trash"></i></button></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-info font-weight-bold mb-2" id="add-product-row"><i class="fas fa-plus"></i> Add Product</button>
                <div class="form-row mt-3 align-items-center">
                    <div class="col-md-9 text-right pr-0"><span class="h5 font-weight-bold">Total:</span></div>
                    <div class="col-md-3">
                        <input type="text" id="order-total" class="form-control form-control-lg bg-white border-primary text-primary font-weight-bold" readonly name="order_total" value="{{ number_format($order->total,2) }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-warning text-dark">Attachments & Status</div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Attachment (Invoice/Document)</label>
                        <input type="file" name="attachment" class="form-control">
                        @if($order->attachment)
                            <a href="{{ asset('storage/'.$order->attachment) }}" target="_blank">View Current</a>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="pending" {{ $order->status=='pending'?'selected':'' }}>Pending</option>
                            <option value="ordered" {{ $order->status=='ordered'?'selected':'' }}>Ordered</option>
                            <option value="received" {{ $order->status=='received'?'selected':'' }}>Received</option>
                            <option value="cancelled" {{ $order->status=='cancelled'?'selected':'' }}>Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb-4">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Update</button>
            <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary"><i class="fas fa-times mr-1"></i> Cancel</a>
        </div>
    </form>
</div>
@endsection
