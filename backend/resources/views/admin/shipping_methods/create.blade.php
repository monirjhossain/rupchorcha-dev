@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Add Shipping Method</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('shipping-methods.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="type">Type</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="flat">Flat Rate</option>
                        <option value="free">Free Shipping</option>
                        <option value="pickup">Local Pickup</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cost">Cost</label>
                    <input type="number" step="0.01" name="cost" id="cost" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="min_order">Minimum Order (optional)</label>
                    <input type="number" step="0.01" name="min_order" id="min_order" class="form-control">
                </div>
                <div class="form-group">
                    <label for="max_order">Maximum Order (optional)</label>
                    <input type="number" step="0.01" name="max_order" id="max_order" class="form-control">
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="sort_order">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="0">
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('shipping-methods.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
