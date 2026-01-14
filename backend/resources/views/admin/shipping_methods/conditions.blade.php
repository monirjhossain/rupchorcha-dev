@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 font-weight-bold text-primary"><i class="fas fa-shipping-fast mr-2"></i>{{ $method->name }} <span class="badge badge-secondary">Conditions</span></h1>
        <a href="{{ route('shipping-methods.index') }}" class="btn btn-secondary shadow-sm"><i class="fa fa-arrow-left"></i> Back to Methods</a>
    </div>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="font-weight-bold mb-3">Add Shipping Condition</h5>
            <form action="{{ route('shipping-methods.conditions.store', $method) }}" method="POST" class="form-inline mb-4">
                @csrf
                <div class="form-group mr-2 mb-2">
                    <label for="condition_type" class="mr-2">Type</label>
                    <select name="condition_type" id="condition_type" class="form-control" required>
                        <option value="product">Product</option>
                        <option value="category">Category</option>
                        <option value="brand">Brand</option>
                        <option value="min_order">Min Order</option>
                        <option value="free_shipping">Free Shipping</option>
                    </select>
                </div>
                <div class="form-group mr-2 mb-2" id="condition-value-group">
                    <label for="condition_value" class="mr-2">Value</label>
                    <select name="condition_value" id="condition_value" class="form-control d-none" style="min-width:180px;"></select>
                    <input type="text" name="condition_value" id="condition_value_text" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary mb-2">Add Condition</button>
            </form>
            <h5 class="font-weight-bold mb-3">Current Conditions</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($method->conditions as $condition)
                        <tr>
                            <td><span class="badge badge-info text-uppercase">{{ $condition->condition_type }}</span></td>
                            <td>
                                @if($condition->condition_type === 'brand')
                                    {{ \App\Models\Brand::find($condition->condition_value)?->name ?? $condition->condition_value }}
                                @elseif($condition->condition_type === 'product')
                                    {{ \App\Models\Product::find($condition->condition_value)?->name ?? $condition->condition_value }}
                                @elseif($condition->condition_type === 'category')
                                    {{ \App\Models\Category::find($condition->condition_value)?->name ?? $condition->condition_value }}
                                @else
                                    {{ $condition->condition_value }}
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('shipping-methods.conditions.destroy', [$method, $condition]) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this condition?')"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No conditions set for this method.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function() {
    var products = @json(App\Models\Product::select('id','name')->orderBy('name')->get());
    var categories = @json(App\Models\Category::select('id','name')->orderBy('name')->get());
    var brands = @json(App\Models\Brand::select('id','name')->orderBy('name')->get());
    function updateValueInput(type) {
        var $select = $('#condition_value');
        var $text = $('#condition_value_text');
        $select.empty();
        if(type === 'product') {
            $select.removeClass('d-none').attr('name','condition_value');
            $text.addClass('d-none').removeAttr('name');
            products.forEach(function(p){ $select.append('<option value="'+p.id+'">'+p.name+'</option>'); });
        } else if(type === 'category') {
            $select.removeClass('d-none').attr('name','condition_value');
            $text.addClass('d-none').removeAttr('name');
            categories.forEach(function(c){ $select.append('<option value="'+c.id+'">'+c.name+'</option>'); });
        } else if(type === 'brand') {
            $select.removeClass('d-none').attr('name','condition_value');
            $text.addClass('d-none').removeAttr('name');
            brands.forEach(function(b){ $select.append('<option value="'+b.id+'">'+b.name+'</option>'); });
        } else if(type === 'free_shipping') {
            $select.addClass('d-none').removeAttr('name');
            $text.removeClass('d-none').attr('name','condition_value').val('yes').prop('readonly',true);
        } else {
            $select.addClass('d-none').removeAttr('name');
            $text.removeClass('d-none').attr('name','condition_value').val('').prop('readonly',false);
        }
    }
    $('#condition_type').on('change', function() {
        updateValueInput($(this).val());
    });
    updateValueInput($('#condition_type').val());
});
</script>
@endpush
