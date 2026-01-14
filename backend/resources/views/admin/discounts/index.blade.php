@extends('layouts.admin')
@section('title', 'Discounts')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 font-weight-bold text-primary"><i class="fas fa-percentage mr-2"></i>Discounts</h1>
        <a href="{{ route('discounts.create') }}" class="btn btn-success shadow-sm"><i class="fa fa-plus"></i> Add Discount</a>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Active</th>
                        <th>Start</th>
                        <th>Expires</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($discounts as $discount)
                    <tr>
                        <td>{{ $discount->id }}</td>
                        <td>{{ $discount->title }}</td>
                        <td>{{ ucfirst($discount->type) }}</td>
                        <td>{{ $discount->discount_value }} {{ $discount->discount_type == 'percent' ? '%' : '৳' }}</td>
                        <td>{{ $discount->active ? 'Yes' : 'No' }}</td>
                        <td>{{ $discount->start_at ? $discount->start_at->format('Y-m-d') : '-' }}</td>
                        <td>{{ $discount->expires_at ? $discount->expires_at->format('Y-m-d') : '-' }}</td>
                        <td>
                            <a href="{{ route('discounts.edit', $discount->id) }}" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                            <form action="{{ route('discounts.destroy', $discount->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3 d-flex justify-content-center">
                {{ $discounts->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
