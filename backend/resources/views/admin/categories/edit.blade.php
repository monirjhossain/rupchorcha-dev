@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Category</h1>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">Category Information</div>
                <div class="card-body">
                    <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Slug</label>
                                <input type="text" name="slug" class="form-control" value="{{ $category->slug }}" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Parent Category</label>
                                <select name="parent_id" class="form-control">
                                    <option value="">None (Main Category)</option>
                                    @if(isset($categories))
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" @if($category->parent_id == $cat->id) selected @endif>{{ $cat->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Description</label>
                                <input type="text" name="description" class="form-control" value="{{ $category->description }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Banner Image</label>
                                <input type="file" name="banner_image" id="bannerInput" class="form-control">
                                @if($category->banner_image)
                                    <small class="form-text text-muted">Current banner exists</small>
                                @endif
                                <img id="bannerPreview" src="" alt="Banner Image Preview" style="max-width: 200px; max-height: 200px; margin-top: 10px; display: none; border-radius: 5px;">
                            </div>
                        </div>
                        <div class="text-right mt-3">
                            <button type="submit" class="btn btn-success">Update</button>
                            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Banner preview
        const bannerInput = document.getElementById('bannerInput');
        const bannerPreview = document.getElementById('bannerPreview');
        if(bannerInput) {
            bannerInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if(file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        bannerPreview.src = event.target.result;
                        bannerPreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    bannerPreview.style.display = 'none';
                }
            });
        }
    });
</script>
@endpush