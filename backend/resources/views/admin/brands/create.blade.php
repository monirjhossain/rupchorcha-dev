@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Add Brand</h1>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">Brand Information</div>
                <div class="card-body">
                    <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Slug</label>
                                <input type="text" name="slug" class="form-control">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Brand Image</label>
                                <input type="file" name="image" id="imageInput" class="form-control">
                                <img id="imagePreview" src="" alt="Brand Image Preview" style="max-width: 200px; max-height: 200px; margin-top: 10px; display: none; border-radius: 5px;">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Banner Image</label>
                                <input type="file" name="banner_image" id="bannerInput" class="form-control">
                                <img id="bannerPreview" src="" alt="Banner Image Preview" style="max-width: 200px; max-height: 200px; margin-top: 10px; display: none; border-radius: 5px;">
                            </div>
                        </div>
                        <div class="text-right mt-3">
                            <button type="submit" class="btn btn-primary" onclick="toastr.success('Brand will be saved!')">Save</button>
                            <a href="{{ route('brands.index') }}" class="btn btn-secondary" onclick="toastr.info('Cancelled brand creation')">Cancel</a>
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
        const nameInput = document.querySelector('input[name="name"]');
        const slugInput = document.querySelector('input[name="slug"]');
        if(nameInput && slugInput) {
            nameInput.addEventListener('input', function() {
                let slug = nameInput.value.toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
                slugInput.value = slug;
            });
        }

        // Image preview functionality
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        if(imageInput) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if(file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        imagePreview.src = event.target.result;
                        imagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.style.display = 'none';
                }
            });
        }

        // Banner preview functionality
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
