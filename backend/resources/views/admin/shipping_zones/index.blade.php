@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 font-weight-bold text-primary"><i class="fas fa-globe-asia mr-2"></i>Shipping Zones</h1>
        <a href="{{ route('shipping-zones.create') }}" class="btn btn-success shadow-sm"><i class="fa fa-plus"></i> Add Zone</a>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Zone Name</th>
                            <th>Country/Region</th>
                            <th>Shipping Methods</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($zones as $zone)
                        <tr>
                            <td>{{ $zone->name }}</td>
                            <td>{{ $zone->country }}{{ $zone->region ? ', ' . $zone->region : '' }}</td>
                            <td>
                                <span class="badge badge-info">{{ $zone->shippingMethods->count() }}</span>
                                <a href="{{ route('shipping-zones.show', $zone) }}" class="ml-2">View Methods</a>
                            </td>
                            <td>
                                <a href="{{ route('shipping-zones.edit', $zone) }}" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                                <form action="{{ route('shipping-zones.destroy', $zone) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this zone?')"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No shipping zones found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
