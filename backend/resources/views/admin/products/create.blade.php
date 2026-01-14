@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><i class="fas fa-box-open"></i> Add Product</h1>
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white"><i class="fas fa-info-circle mr-2"></i> Basic Information</div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required placeholder="Product Name">
                                <small class="form-text text-muted">Enter the product name.</small>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Slug <span class="text-danger">*</span></label>
                                <input type="text" name="slug" class="form-control" required placeholder="Auto-generated">
                                <small class="form-text text-muted">URL-friendly version, auto-generated.</small>
                            </div>
                        </div>
                                                <!-- Supplier dropdown moved to Pricing & Inventory box below -->
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Description <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control" rows="2" required placeholder="Full product description"></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Short Description</label>
                                <textarea name="short_description" class="form-control" rows="2" placeholder="Short summary"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-info text-white"><i class="fas fa-tags mr-2"></i> Pricing & Inventory</div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">৳</span></div>
                                    <input type="number" name="price" class="form-control" required step="0.01" placeholder="0.00">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Sale Price</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">৳</span></div>
                                    <input type="number" name="sale_price" class="form-control" step="0.01" placeholder="0.00">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Cost Price</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">৳</span></div>
                                    <input type="number" name="cost_price" class="form-control" step="0.01" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Stock Quantity <span class="text-danger">*</span></label>
                                <input type="number" name="stock_quantity" class="form-control" required placeholder="0">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Warehouse</label>
                                <select name="warehouse_id" class="form-control">
                                    <option value="">Select Warehouse</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>SKU</label>
                                <input type="text" name="sku" class="form-control" placeholder="Stock Keeping Unit">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Supplier</label>
                                <select name="supplier_id" class="form-control">
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Manage Stock</label>
                                <select name="manage_stock" class="form-control">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Type <span class="text-danger">*</span></label>
                                <input type="text" name="type" class="form-control" value="simple" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Featured</label>
                                <select name="featured" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Min Order Qty</label>
                                <input type="number" name="min_order_qty" class="form-control" placeholder="Minimum">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Max Order Qty</label>
                                <input type="number" name="max_order_qty" class="form-control" placeholder="Maximum">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-secondary text-white"><i class="fas fa-search mr-2"></i> SEO & Extra</div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Meta Title</label>
                                <input type="text" name="meta_title" class="form-control" placeholder="Meta Title">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Meta Description</label>
                                <input type="text" name="meta_description" class="form-control" placeholder="Meta Description">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>External URL</label>
                                <input type="text" name="external_url" class="form-control" placeholder="External Link">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Sale Start Date</label>
                                <input type="datetime-local" name="sale_start_date" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Sale End Date</label>
                                <input type="datetime-local" name="sale_end_date" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-success text-white"><i class="fas fa-list-alt mr-2"></i> Category & Brand</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Brand <span class="text-danger">*</span></label>
                            <select name="brand_id" class="form-control" required>
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @include('admin.products.partials.tags')
                    </div>
                </div>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-warning text-dark"><i class="fas fa-image mr-2"></i> Main Image</div>
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <input type="file" name="main_image" class="form-control">
                            <small class="form-text text-muted">Upload main product image.</small>
                        </div>
                    </div>
                </div>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-success text-white"><i class="fas fa-images mr-2"></i> Gallery Images</div>
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <input type="file" name="gallery_images[]" class="form-control" multiple>
                            <small class="form-text text-muted">You can select multiple images for the product gallery.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb-4">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary"><i class="fas fa-times mr-1"></i> Cancel</a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<!-- Select2 CSS & JS CDN -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Slug auto-generate
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
        // Initialize Select2 for tags
        if (window.jQuery) {
            $('#tags').select2({
                placeholder: 'Select tags',
                allowClear: true,
                width: '100%'
            });
        }
    });
</script>
@endsection
