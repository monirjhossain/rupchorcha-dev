@extends('layouts.admin')

@section('title', 'Bulk Email Campaign')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-3">Bulk Email Campaign</h2>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-8">
            <form method="GET" action="{{ route('bulk_email.index') }}" class="form-inline mb-2">
                <input type="text" name="search" class="form-control mr-2" placeholder="Search name, email, phone" value="{{ request('search') }}">
                <select name="role" class="form-control mr-2">
                    <option value="">All Roles</option>
                    <option value="customer" @if(request('role')=='customer') selected @endif>Customer</option>
                    <option value="guest" @if(request('role')=='guest') selected @endif>Guest</option>
                    <option value="admin" @if(request('role')=='admin') selected @endif>Admin</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
    </div>
    <form method="POST" action="{{ route('bulk_email.send') }}">
        @csrf
        <div class="card mb-4">
            <div class="card-header bg-light">
                <strong>Select Recipients</strong>
            </div>
            <div class="card-body p-0" style="max-height: 300px; overflow-y: auto;">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td><input type="checkbox" name="user_ids[]" value="{{ $user->id }}"></td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ ucfirst($user->role) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center">No users found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header bg-light">
                <strong>Email Content</strong>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" required>
                    @error('subject')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mt-3">
                    <label for="body">Message</label>
                    <textarea name="body" id="body" rows="6" class="form-control @error('body') is-invalid @enderror" required>{{ old('body') }}</textarea>
                    @error('body')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success btn-lg">Send Bulk Email</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input[name="user_ids[]"]');
        for (const cb of checkboxes) {
            cb.checked = this.checked;
        }
    });
</script>
@endpush
