@extends('layouts.admin')

@section('title', 'Product Reviews')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Product Reviews</h1>
    <div class="card">
        <div class="card-body">
            <form method="GET" action="" class="form-inline mb-3">
                <div class="form-group mr-2">
                    <label for="status" class="mr-2">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">All</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-2">
                    <label for="product_id" class="mr-2">Product</label>
                    <select name="product_id" id="product_id" class="form-control">
                        <option value="">All</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
            <table class="table table-bordered table-hover" id="reviews-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>User</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Status</th>
                        <th>Images</th>
                        <th>Submitted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                        <tr>
                            <td>{{ $review->id }}</td>
                            <td>{{ $review->product->name ?? '-' }}</td>
                            <td>{{ $review->user->name ?? '-' }}</td>
                            <td>{{ $review->rating }}</td>
                            <td>{{ $review->comment }}</td>
                            <td>
                                <span class="badge badge-{{ $review->status == 'approved' ? 'success' : ($review->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($review->status) }}
                                </span>
                            </td>
                            <td>
                                @if($review->images)
                                    @foreach($review->images as $img)
                                        <a href="#" data-toggle="modal" data-target="#imgModal{{ $review->id }}{{ $loop->index }}">
                                            <img src="{{ asset('storage/' . $img) }}" alt="Review Image" width="40" height="40" class="mr-1 mb-1 rounded" style="cursor:pointer;">
                                        </a>
                                        <!-- Modal -->
                                        <div class="modal fade" id="imgModal{{ $review->id }}{{ $loop->index }}" tabindex="-1" role="dialog" aria-labelledby="imgModalLabel{{ $review->id }}{{ $loop->index }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="imgModalLabel{{ $review->id }}{{ $loop->index }}">Review Image</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="{{ asset('storage/' . $img) }}" alt="Review Image" class="img-fluid rounded">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $review->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-sm btn-outline-info mb-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('reviews.updateStatus', $review->id) }}" class="d-inline">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="form-control form-control-sm d-inline-block w-auto">
                                        <option value="approved" {{ $review->status == 'approved' ? 'selected' : '' }}>Approve</option>
                                        <option value="pending" {{ $review->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="rejected" {{ $review->status == 'rejected' ? 'selected' : '' }}>Reject</option>
                                    </select>
                                </form>
                                <form method="POST" action="{{ route('reviews.destroy', $review->id) }}" class="d-inline" onsubmit="return confirm('Delete this review?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-3">
                {{ $reviews->onEachSide(1)->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
