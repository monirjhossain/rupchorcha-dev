@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Product Details</h1>
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>{{ $product->name }}</h4>
                    <p><strong>SKU:</strong> {{ $product->sku }}</p>
                    <p><strong>Price:</strong> ৳{{ number_format($product->price, 2) }}</p>
                    <p><strong>Sale Price:</strong> ৳{{ number_format($product->sale_price, 2) }}</p>
                    <p><strong>Status:</strong> {{ $product->status ? 'Active' : 'Inactive' }}</p>
                    <p><strong>Stock (Current):</strong> <span class="badge badge-success" style="font-size:1.2em;">{{ $product->stock_quantity }}</span></p>
                </div>
                <div class="col-md-6">
                    @if($product->main_image)
                        <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-width:300px;">
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
