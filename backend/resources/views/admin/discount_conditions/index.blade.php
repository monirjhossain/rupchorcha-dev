@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 font-weight-bold text-primary"><i class="fas fa-percent mr-2"></i>Product/Brand/Category Discount</h1>
        <a href="{{ route('discount-conditions.create') }}" class="btn btn-success">Add New Discount Condition</a>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="font-weight-bold mb-3">All Discount Conditions</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Type</th>
                            <th>Target</th>
                            <th>Discount</th>
                            <th>Free Shipping</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($conditions as $condition)
                        <tr>
                            <td class="text-uppercase">{{ $condition->type }}</td>
                            <td>
                                @if($condition->type === 'brand')
                                    {{ \App\Models\Brand::find($condition->target_id)?->name }}
                                @elseif($condition->type === 'product')
                                    {{ \App\Models\Product::find($condition->target_id)?->name }}
                                @elseif($condition->type === 'category')
                                    {{ \App\Models\Category::find($condition->target_id)?->name }}
                                @endif
                            </td>
                            <td>
                                @if($condition->discount_type === 'percentage')
                                    {{ $condition->discount_value }}%
                                @elseif($condition->discount_type === 'fixed')
                                    ৳{{ $condition->discount_value }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($condition->free_shipping)
                                    <span class="badge badge-success">Yes</span>
                                @else
                                    <span class="badge badge-secondary">No</span>
                                @endif
                            </td>
                            <td>{{ $condition->notes }}</td>
                            <td>
                                <a href="{{ route('discount-conditions.edit', $condition->id) }}" class="btn btn-sm btn-info mr-1"><i class="fa fa-edit"></i></a>
                                <form action="{{ route('discount-conditions.destroy', $condition->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this discount condition?')"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No discount conditions found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
