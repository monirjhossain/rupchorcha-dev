@extends('layouts.admin')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Users</h1>
    <div>
        <a href="{{ url('/admin/users/create') }}" class="btn btn-primary">+ Create User</a>
    </div>
</div>
<!-- Bulk SMS Section -->
<form method="POST" action="{{ route('users.bulk_sms') }}">
    @csrf
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="font-weight-bold">Bulk SMS</span>
            <button type="submit" class="btn btn-info">Send SMS</button>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="sms_api_key">SMS API Key</label>
                <input type="text" name="sms_api_key" id="sms_api_key" class="form-control" placeholder="Enter SMS API Key" required>
            </div>
            <div class="form-group">
                <label for="sms_message">Message</label>
                <textarea name="sms_message" id="sms_message" class="form-control" rows="3" required></textarea>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select_all_users"></th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td><input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="user-checkbox"></td>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->address }}</td>
                                <td>{{ $user->active ? 'Active' : 'Inactive' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
@push('scripts')
<script>
    document.getElementById('select_all_users').addEventListener('change', function() {
        let checked = this.checked;
        document.querySelectorAll('.user-checkbox').forEach(function(cb) {
            cb.checked = checked;
        });
    });
</script>
@endpush
<!-- User Search & Filter -->
<form method="GET" action="" class="mb-4">
    <div class="row align-items-end">
        <div class="col-md-3">
            <label for="search">Search</label>
            <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Name, Email, Phone">
        </div>
        <div class="col-md-3">
            <label for="role">Role</label>
            <select name="role" id="role" class="form-control">
                <option value="">All</option>
                <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100 mt-4"><i class="fas fa-search mr-1"></i> Filter</button>
        </div>
    </div>
</form>
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        @if($user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $user->role ?? '')) }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->address }}</td>
                            <td>
                                @if($user->active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                                <form action="{{ route('users.toggle', $user->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning mt-1">{{ $user->active ? 'Deactivate' : 'Activate' }}</button>
                                </form>
                            </td>
                            <td>
                                                                <a href="{{ url('/admin/users/' . $user->id) }}" class="btn btn-sm btn-secondary">View</a>
                                                                <a href="{{ url('/admin/users/' . $user->id . '/edit') }}" class="btn btn-sm btn-info">Edit</a>
                                                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#messageModal{{ $user->id }}">Message</button>
                                                                @if(($user->role ?? '') !== 'super_admin')
                                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                                        @csrf
                                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                                </form>
                                                                @endif
                                                                <!-- Message Modal -->
                                                                <div class="modal fade" id="messageModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel{{ $user->id }}" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <form method="POST" action="{{ route('users.message', $user->id) }}">
                                                                                @csrf
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="messageModalLabel{{ $user->id }}">Send Message to {{ $user->name }}</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="form-group">
                                                                                        <label for="message_type_{{ $user->id }}">Type</label>
                                                                                        <select name="message_type" id="message_type_{{ $user->id }}" class="form-control" required>
                                                                                            <option value="email">Email</option>
                                                                                            <option value="sms">SMS</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label for="message_{{ $user->id }}">Message</label>
                                                                                        <textarea name="message" id="message_{{ $user->id }}" class="form-control" rows="3" required></textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                                    <button type="submit" class="btn btn-primary">Send</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
