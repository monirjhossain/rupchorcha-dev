@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Shipping Method</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('shipping-methods.update', $shippingMethod) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $shippingMethod->name }}" required>
                </div>
                <div class="form-group">
                    <label for="type">Type</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="flat" @if($shippingMethod->type=='flat') selected @endif>Flat Rate</option>
                        <option value="free" @if($shippingMethod->type=='free') selected @endif>Free Shipping</option>
                        <option value="pickup" @if($shippingMethod->type=='pickup') selected @endif>Local Pickup</option>
                        <option value="custom" @if($shippingMethod->type=='custom') selected @endif>Custom</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cost">Cost</label>
                    <input type="number" step="0.01" name="cost" id="cost" class="form-control" value="{{ $shippingMethod->cost }}" required>
                </div>
                <div class="form-group">
                    <label for="min_order">Minimum Order (optional)</label>
                    <input type="number" step="0.01" name="min_order" id="min_order" class="form-control" value="{{ $shippingMethod->min_order }}">
                </div>
                <div class="form-group">
                    <label for="max_order">Maximum Order (optional)</label>
                    <input type="number" step="0.01" name="max_order" id="max_order" class="form-control" value="{{ $shippingMethod->max_order }}">
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="1" @if($shippingMethod->status) selected @endif>Active</option>
                        <option value="0" @if(!$shippingMethod->status) selected @endif>Inactive</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="sort_order">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ $shippingMethod->sort_order }}">
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('shipping-methods.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
