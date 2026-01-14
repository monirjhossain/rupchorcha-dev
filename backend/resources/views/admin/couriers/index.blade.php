@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 font-weight-bold text-primary"><i class="fas fa-truck mr-2"></i>Courier Management</h1>
        <a href="{{ route('couriers.create') }}" class="btn btn-success">Add New Courier</a>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="font-weight-bold mb-3">All Couriers</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Logo</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Service Area</th>
                            <th>Delivery Types</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($couriers as $courier)
                        <tr>
                            <td>
                                @if($courier->logo)
                                    <img src="{{ asset('storage/' . $courier->logo) }}" alt="Logo" style="height:32px;">
                                @endif
                            </td>
                            <td>{{ $courier->name }}</td>
                            <td>{{ $courier->contact_number }}</td>
                            <td>{{ $courier->email }}</td>
                            <td>{{ $courier->service_area }}</td>
                            <td>{{ $courier->delivery_types }}</td>
                            <td>
                                @if($courier->status)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('couriers.edit', $courier->id) }}" class="btn btn-sm btn-info mr-1"><i class="fa fa-edit"></i></a>
                                <form action="{{ route('couriers.destroy', $courier->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this courier?')"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No couriers found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
