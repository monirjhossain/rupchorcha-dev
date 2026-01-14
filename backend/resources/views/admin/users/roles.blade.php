@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Role Permissions</h1>
    <form method="POST" action="{{ route('roles.update') }}">
        @csrf
        <div class="form-group">
            <label for="role">Select Role</label>
            <select name="role" id="role" class="form-control" required onchange="this.form.submit()">
                <option value="">-- Select Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
        </div>
        @if(request('role'))
        <div class="form-group">
            <label>Permissions</label>
            <div class="row">
                @foreach($permissions as $perm)
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm->id }}"
                                id="perm_{{ $perm->id }}"
                                {{ $rolePermissions->where('role', request('role'))->where('permission_id', $perm->id)->count() ? 'checked' : '' }}>
                            <label class="form-check-label" for="perm_{{ $perm->id }}">{{ $perm->label ?? $perm->name }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Permissions</button>
        @endif
    </form>
</div>
@endsection
