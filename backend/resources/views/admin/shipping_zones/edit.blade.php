@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Shipping Zone</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('shipping-zones.update', $zone) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Zone Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $zone->name }}" required>
                </div>
                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" name="country" id="country" class="form-control" value="{{ $zone->country }}" required>
                </div>
                <div class="form-group">
                    <label for="region">Region</label>
                    <input type="text" name="region" id="region" class="form-control" value="{{ $zone->region }}">
                </div>
                <div class="form-group">
                    <label for="postcode">Postcode</label>
                    <input type="text" name="postcode" id="postcode" class="form-control" value="{{ $zone->postcode }}">
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('shipping-zones.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
