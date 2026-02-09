@extends('layouts.admin')

@section('title', 'Add Customer Address')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Add Customer Address</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('addresses.store') }}">
                @csrf
                <div class="form-group">
                    <label for="user_id">User</label>
                    <select name="user_id" id="user_id" class="form-control" required></select>
                </div>
                <div class="form-group">
                    <label for="type">Type</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="shipping">Shipping</option>
                        <option value="billing">Billing</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control">
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control">
                </div>
                <div class="form-group">
                    <label for="address_line1">Address Line 1</label>
                    <input type="text" name="address_line1" id="address_line1" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="address_line2">Address Line 2</label>
                    <input type="text" name="address_line2" id="address_line2" class="form-control">
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" name="city" id="city" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="state">State</label>
                    <input type="text" name="state" id="state" class="form-control">
                </div>
                <div class="form-group">
                    <label for="postal_code">Postal Code</label>
                    <input type="text" name="postal_code" id="postal_code" class="form-control">
                </div>
                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" name="country" id="country" class="form-control" value="Bangladesh" required>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" name="is_default" id="is_default" class="form-check-input" value="1">
                    <label class="form-check-label" for="is_default">Set as Default</label>
                </div>
                <button type="submit" class="btn btn-primary">Add Address</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 38px;
        line-height: 38px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#user_id').select2({
        placeholder: 'Search for a user...',
        allowClear: true,
        ajax: {
            url: '{{ route("users.ajax_search") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term // search term
                };
            },
            processResults: function (data) {
                return {
                    results: data.data.results
                };
            },
            cache: true
        }
    });
});
</script>
@endpush
