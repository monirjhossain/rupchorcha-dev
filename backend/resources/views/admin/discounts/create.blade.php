@extends('layouts.admin')
@section('title', 'Add Discount')
@section('content')
<div class="d-flex flex-column align-items-center justify-content-start flex-grow-1 w-100 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 w-100" style="max-width: 900px;">
        <h1 class="h3 mb-0 font-weight-bold text-primary"><i class="fas fa-percentage mr-2"></i>Add Discount</h1>
        <a href="{{ route('discounts.index') }}" class="btn btn-outline-secondary shadow-sm"><i class="fa fa-arrow-left"></i> Back</a>
    </div>
    <div class="w-100" style="max-width: 900px;">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('discounts.store') }}" method="POST">
                    @csrf
                    @include('admin.discounts.partials.form', ['discount' => null, 'products' => $products, 'categories' => $categories, 'brands' => $brands])
                    <div class="text-right mt-4">
                        <button type="submit" class="btn btn-success px-4 shadow-sm"><i class="fa fa-check mr-1"></i> Create Discount</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2-multiple').select2({
        width: '100%',
        placeholder: 'Select...',
        allowClear: true
    });
});
</script>
@endsection
