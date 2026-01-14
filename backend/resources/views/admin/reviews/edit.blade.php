@extends('layouts.admin')

@section('title', 'Edit Product Review')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Edit Product Review</h1>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('reviews.update', $review->id) }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="rating">Rating</label>
                    <select name="rating" id="rating" class="form-control" required>
                        @for($i=1; $i<=5; $i++)
                            <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group">
                    <label for="comment">Comment</label>
                    <textarea name="comment" id="comment" class="form-control" rows="3">{{ $review->comment }}</textarea>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="pending" {{ $review->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $review->status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $review->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Review</button>
                <a href="{{ route('reviews.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
