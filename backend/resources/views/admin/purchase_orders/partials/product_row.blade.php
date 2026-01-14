@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><i class="fas fa-plus"></i> Add Product Row</h1>
    <div class="form-row product-row align-items-end">
        <div class="form-group col-md-5">
            <label>Product</label>
            <select name="product_id[]" class="form-control product-select" required>
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-2">
            <label>Quantity</label>
            <input type="number" name="quantity[]" class="form-control quantity-input" min="1" value="1" required>
        </div>
        <div class="form-group col-md-2">
            <label>Unit Price</label>
            <input type="number" name="unit_price[]" class="form-control unit-price-input" min="0" step="0.01" required>
        </div>
        <div class="form-group col-md-2">
            <label>Subtotal</label>
            <input type="text" class="form-control subtotal-input" readonly>
        </div>
        <div class="form-group col-md-1">
            <button type="button" class="btn btn-danger remove-row"><i class="fas fa-trash"></i></button>
        </div>
    </div>
</div>
@endsection
