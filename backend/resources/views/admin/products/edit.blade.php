@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><i class="fas fa-edit"></i> Edit Product</h1>
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white"><i class="fas fa-info-circle mr-2"></i> Basic Information</div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ $product->name }}" required placeholder="Product Name">
                                <small class="form-text text-muted">Enter the product name.</small>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Slug <span class="text-danger">*</span></label>
                                <input type="text" name="slug" class="form-control" value="{{ $product->slug }}" required placeholder="Auto-generated">
                                <small class="form-text text-muted">URL-friendly version, auto-generated.</small>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Description <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control" rows="2" required placeholder="Full product description">{{ $product->description }}</textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Short Description</label>
                                <textarea name="short_description" class="form-control" rows="2" placeholder="Short summary">{{ $product->short_description }}</textarea>
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
                                    <input type="number" name="price" class="form-control" value="{{ $product->price }}" required step="0.01" placeholder="0.00">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Sale Price</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">৳</span></div>
                                    <input type="number" name="sale_price" class="form-control" value="{{ $product->sale_price }}" step="0.01" placeholder="0.00">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Cost Price</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">৳</span></div>
                                    <input type="number" name="cost_price" class="form-control" value="{{ $product->cost_price }}" step="0.01" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Stock Quantity <span class="text-danger">*</span></label>
                                <input type="number" name="stock_quantity" class="form-control" value="{{ $product->stock_quantity }}" required placeholder="0">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Warehouse</label>
                                <select name="warehouse_id" class="form-control">
                                    <option value="">Select Warehouse</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}" @if($product->warehouse_id == $warehouse->id) selected @endif>{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>SKU</label>
                                <input type="text" name="sku" class="form-control" value="{{ $product->sku }}" placeholder="Stock Keeping Unit">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Type <span class="text-danger">*</span></label>
                                <input type="text" name="type" class="form-control" value="{{ $product->type }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="active" @if($product->status=='active') selected @endif>Active</option>
                                    <option value="inactive" @if($product->status=='inactive') selected @endif>Inactive</option>
                                    <option value="draft" @if($product->status=='draft') selected @endif>Draft</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Featured</label>
                                <select name="featured" class="form-control">
                                    <option value="0" @if(!$product->featured) selected @endif>No</option>
                                    <option value="1" @if($product->featured) selected @endif>Yes</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Supplier</label>
                                <select name="supplier_id" class="form-control">
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" @if($product->supplier_id == $supplier->id) selected @endif>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Barcode</label>
                                <input type="text" name="barcode" class="form-control" value="{{ $product->barcode }}" placeholder="Barcode">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Manage Stock</label>
                                <select name="manage_stock" class="form-control">
                                    <option value="1" @if($product->manage_stock) selected @endif>Yes</option>
                                    <option value="0" @if(!$product->manage_stock) selected @endif>No</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Min Order Qty</label>
                                <input type="number" name="min_order_qty" class="form-control" value="{{ $product->min_order_qty }}" placeholder="Minimum">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Max Order Qty</label>
                                <input type="number" name="max_order_qty" class="form-control" value="{{ $product->max_order_qty }}" placeholder="Maximum">
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
                                <input type="text" name="meta_title" class="form-control" value="{{ $product->meta_title }}" placeholder="Meta Title">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Meta Description</label>
                                <input type="text" name="meta_description" class="form-control" value="{{ $product->meta_description }}" placeholder="Meta Description">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>External URL</label>
                                <input type="text" name="external_url" class="form-control" value="{{ $product->external_url }}" placeholder="External Link">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Sale Start Date</label>
                                <input type="datetime-local" name="sale_start_date" class="form-control" value="{{ $product->sale_start_date ? date('Y-m-d\TH:i', strtotime($product->sale_start_date)) : '' }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Sale End Date</label>
                                <input type="datetime-local" name="sale_end_date" class="form-control" value="{{ $product->sale_end_date ? date('Y-m-d\TH:i', strtotime($product->sale_end_date)) : '' }}">
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
                                    <option value="{{ $category->id }}" @if($product->category_id == $category->id) selected @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Brand <span class="text-danger">*</span></label>
                            <select name="brand_id" class="form-control" required>
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" @if($product->brand_id == $brand->id) selected @endif>{{ $brand->name }}</option>
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
                            @if($product->main_image)
                                <img src="{{ asset('storage/' . $product->main_image) }}" alt="Main Image" class="img-thumbnail mt-2" style="max-width: 150px;">
                            @endif
                            <small class="form-text text-muted">Upload main product image.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-4">
            <div class="alert alert-info">
                <strong>Current Stock:</strong> {{ $product->current_stock }}
            </div>
            <a href="{{ route('products.stock_movement.create', $product->id) }}" class="btn btn-warning mb-2">
                <i class="fas fa-exchange-alt mr-1"></i> Stock Movement
            </a>
            <div class="card mt-3">
                <div class="card-header bg-secondary text-white">Stock Movement Log</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>Reason</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($product->stockMovements()->latest()->limit(10)->get() as $movement)
                            <tr>
                                <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ ucfirst($movement->type) }}</td>
                                <td>{{ $movement->quantity }}</td>
                                <td>{{ $movement->reason }}</td>
                                <td>{{ $movement->user->name ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">No stock movements found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="text-right mb-4">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Update</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary"><i class="fas fa-times mr-1"></i> Cancel</a>
        </div>
    </form>
</div>
@endsection
