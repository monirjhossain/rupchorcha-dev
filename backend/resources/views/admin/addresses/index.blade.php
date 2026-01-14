@extends('layouts.admin')

@section('title', 'Customer Addresses')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Customer Addresses</h1>
    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('addresses.create') }}" class="btn btn-primary mr-2">Add Address</a>
        <a href="{{ route('addresses.export') }}" class="btn btn-success">Export Customers</a>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('addresses.bulk_sms') }}">
                @csrf
                <div class="d-flex justify-content-end mb-2">
                    <button type="submit" class="btn btn-info">Send SMS</button>
                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select_all"></th>
                            <th>ID</th>
                            <th>User</th>
                            <th>Type</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>Country</th>
                            <th>Default</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($addresses as $address)
                            <tr>
                                <td><input type="checkbox" name="address_ids[]" value="{{ $address->id }}" class="address-checkbox"></td>
                                <td>{{ $address->id }}</td>
                                <td>{{ $address->user ? $address->user->name : '-' }}</td>
                                <td>{{ ucfirst($address->type) }}</td>
                                <td>{{ $address->name }}</td>
                                <td>{{ $address->phone }}</td>
                                <td>{{ $address->address_line1 }} {{ $address->address_line2 }}</td>
                                <td>{{ $address->city }}</td>
                                <td>{{ $address->country }}</td>
                                <td>@if($address->is_default) <span class="badge badge-success">Yes</span> @else <span class="badge badge-secondary">No</span> @endif</td>
                                <td>{{ $address->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('addresses.edit', $address->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('addresses.destroy', $address->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this address?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div>
                    {{ $addresses->links() }}
                </div>
                <div class="form-group mt-3">
                    <label for="sms_message">Message</label>
                    <textarea class="form-control" name="sms_message" id="sms_message" rows="3" required></textarea>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
    <script>
        document.getElementById('select_all').addEventListener('change', function() {
            let checked = this.checked;
            document.querySelectorAll('.address-checkbox').forEach(function(cb) {
                cb.checked = checked;
            });
        });
    </script>
    @endpush
</div>
@endsection
