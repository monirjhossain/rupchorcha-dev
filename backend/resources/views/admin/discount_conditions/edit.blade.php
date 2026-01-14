@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 font-weight-bold text-primary"><i class="fas fa-percent mr-2"></i>Edit Discount Condition</h1>
        <a href="{{ route('discount-conditions.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('discount-conditions.update', $condition->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-row align-items-end">
                    <div class="form-group col-md-3">
                        <label for="type">Type</label>
                        <select name="type" id="type" class="form-control" required readonly>
                            <option value="brand" {{ $condition->type == 'brand' ? 'selected' : '' }}>Brand</option>
                            <option value="product" {{ $condition->type == 'product' ? 'selected' : '' }}>Product</option>
                            <option value="category" {{ $condition->type == 'category' ? 'selected' : '' }}>Category</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="target_id">Target</label>
                        <select name="target_id" id="target_id" class="form-control" required readonly>
                            @if($condition->type == 'brand')
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $condition->target_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            @elseif($condition->type == 'product')
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ $condition->target_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                @endforeach
                            @elseif($condition->type == 'category')
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $condition->target_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="discount_type">Discount Type</label>
                        <select name="discount_type" id="discount_type" class="form-control" required>
                            <option value="percentage" {{ $condition->discount_type == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                            <option value="fixed" {{ $condition->discount_type == 'fixed' ? 'selected' : '' }}>Fixed (৳)</option>
                            <option value="free_shipping" {{ $condition->discount_type == 'free_shipping' ? 'selected' : '' }}>Free Shipping</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="discount_value">Discount Value</label>
                        <input type="number" step="0.01" min="0" name="discount_value" id="discount_value" class="form-control" value="{{ $condition->discount_value }}">
                    </div>
                    <div class="form-group col-md-1">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">Update</button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <input type="text" name="notes" id="notes" class="form-control" value="{{ $condition->notes }}" placeholder="Optional notes">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $('#discount_type').on('change', function() {
        if($(this).val() === 'free_shipping') {
            $('#discount_value').val('').prop('readonly', true).prop('required', false);
        } else {
            $('#discount_value').prop('readonly', false).prop('required', true);
        }
    }).trigger('change');
</script>
@endpush
