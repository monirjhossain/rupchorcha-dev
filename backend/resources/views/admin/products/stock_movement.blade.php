@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Stock Movement for {{ $product->name }}</h2>
    <form method="POST" action="{{ route('products.stock_movement.store', $product->id) }}">
        @csrf
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select name="type" id="type" class="form-control" required>
                <option value="in">Stock In</option>
                <option value="out">Stock Out</option>
                <option value="adjustment">Adjustment</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="reason" class="form-label">Reason (optional)</label>
            <input type="text" name="reason" id="reason" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
