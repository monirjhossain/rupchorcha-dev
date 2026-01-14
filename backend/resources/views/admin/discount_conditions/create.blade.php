@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 font-weight-bold text-primary"><i class="fas fa-percent mr-2"></i>Add Discount Condition</h1>
        <a href="{{ route('discount-conditions.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('discount-conditions.store') }}" method="POST">
                @csrf
                <div class="form-row align-items-end">
                    <div class="form-group col-md-3">
                        <label for="type">Type</label>
                        <select name="type" id="type" class="form-control" required>
                            <option value="brand">Brand</option>
                            <option value="product">Product</option>
                            <option value="category">Category</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="target_id">Target</label>
                        <select name="target_id" id="target_id" class="form-control" required></select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="discount_type">Discount Type</label>
                        <select name="discount_type" id="discount_type" class="form-control" required>
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed (৳)</option>
                            <option value="free_shipping">Free Shipping</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="discount_value">Discount Value</label>
                        <input type="number" step="0.01" min="0" name="discount_value" id="discount_value" class="form-control">
                    </div>
                    <div class="form-group col-md-1">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">Add</button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <input type="text" name="notes" id="notes" class="form-control" placeholder="Optional notes">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    const products = @json($products);
    const brands = @json($brands);
    const categories = @json($categories);
    function updateTargetOptions(type) {
        let options = '';
        if(type === 'brand') {
            brands.forEach(b => options += `<option value="${b.id}">${b.name}</option>`);
        } else if(type === 'product') {
            products.forEach(p => options += `<option value="${p.id}">${p.name}</option>`);
        } else if(type === 'category') {
            categories.forEach(c => options += `<option value="${c.id}">${c.name}</option>`);
        }
        $('#target_id').html(options);
    }
    $(document).ready(function() {
        updateTargetOptions($('#type').val());
        $('#type').on('change', function() {
            updateTargetOptions($(this).val());
        });
        $('#discount_type').on('change', function() {
            if($(this).val() === 'free_shipping') {
                $('#discount_value').val('').prop('readonly', true).prop('required', false);
            } else {
                $('#discount_value').prop('readonly', false).prop('required', true);
            }
        }).trigger('change');
    });
</script>
@endpush
