@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 font-weight-bold text-primary"><i class="fas fa-truck mr-2"></i>Add Courier</h1>
        <a href="{{ route('couriers.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('couriers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="name">Courier Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="api_key">API Key</label>
                        <input type="text" name="api_key" id="api_key" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="logo">Logo</label>
                        <input type="file" name="logo" id="logo" class="form-control-file">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="contact_number">Contact Number</label>
                        <input type="text" name="contact_number" id="contact_number" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="tracking_url">Tracking URL</label>
                        <input type="url" name="tracking_url" id="tracking_url" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="service_area">Service Area</label>
                        <input type="text" name="service_area" id="service_area" class="form-control" placeholder="Dhaka, Nationwide, etc.">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="delivery_types">Delivery Types</label>
                        <input type="text" name="delivery_types" id="delivery_types" class="form-control" placeholder="Regular, Express, COD, etc.">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="notes">Notes</label>
                        <input type="text" name="notes" id="notes" class="form-control">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Add Courier</button>
            </form>
        </div>
    </div>
</div>
@endsection
