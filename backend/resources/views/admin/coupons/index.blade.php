@extends('layouts.admin')
@section('title', 'Coupons')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 font-weight-bold text-primary"><i class="fas fa-ticket-alt mr-2"></i>Coupons</h1>
        <a href="{{ route('coupons.create') }}" class="btn btn-success shadow-sm"><i class="fa fa-plus"></i> Add Coupon</a>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Active</th>
                        <th>Start</th>
                        <th>Expires</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coupons as $coupon)
                    <tr>
                        <td>{{ $coupon->id }}</td>
                        <td>{{ $coupon->code }}</td>
                        <td>{{ ucfirst($coupon->type) }}</td>
                        <td>{{ $coupon->value }}</td>
                        <td>{{ $coupon->active ? 'Yes' : 'No' }}</td>
                        <td>{{ $coupon->start_at ? $coupon->start_at->format('Y-m-d') : '-' }}</td>
                        <td>{{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '-' }}</td>
                        <td>
                            <a href="{{ route('coupons.edit', $coupon->id) }}" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                            <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?');">
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
                {{ $coupons->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
