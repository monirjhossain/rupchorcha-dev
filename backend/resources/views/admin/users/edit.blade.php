@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mt-4">
            <div class="card-header">Edit User</div>
            <div class="card-body">
                <form method="POST" action="{{ url('/admin/users/' . $user->id) }}">
                    @csrf
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <option value="customer" @if($user->role=='customer') selected @endif>Customer</option>
                            <option value="content_manager" @if($user->role=='content_manager') selected @endif>Content Manager</option>
                            <option value="shop_manager" @if($user->role=='shop_manager') selected @endif>Shop Manager</option>
                            <option value="admin" @if($user->role=='admin') selected @endif>Admin</option>
                            <option value="super_admin" @if($user->role=='super_admin') selected @endif>Super Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control" value="{{ $user->address }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
