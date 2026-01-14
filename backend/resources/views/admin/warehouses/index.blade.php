@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Warehouses</h1>
    <a href="{{ route('warehouses.create') }}" class="btn btn-primary mb-3">+ Add Warehouse</a>
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Manager</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($warehouses as $warehouse)
                    <tr>
                        <td>{{ $warehouse->name }}</td>
                        <td>{{ $warehouse->location }}</td>
                        <td>{{ $warehouse->manager }}</td>
                        <td>{{ $warehouse->phone }}</td>
                        <td>
                            <a href="{{ route('warehouses.edit', $warehouse->id) }}" class="btn btn-sm btn-info">Edit</a>
                            <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">No warehouses found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $warehouses->links() }}</div>
    </div>
</div>
@endsection
