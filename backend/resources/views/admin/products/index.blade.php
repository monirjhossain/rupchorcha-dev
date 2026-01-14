@extends('layouts.admin')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Products</h1>
    <div>
        <a href="{{ route('products.create') }}" class="btn btn-primary">+ Add Product</a>
        <button type="button" class="btn btn-success ml-2" data-toggle="modal" data-target="#exportProductModal">Export Product</button>
    </div>
</div>
<!-- Export Product Modal -->
<div class="modal fade" id="exportProductModal" tabindex="-1" role="dialog" aria-labelledby="exportProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('products.export') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exportProductModalLabel">Export Products</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Select Fields to Export</label>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="fields[]" value="id" id="field_id" checked><label class="form-check-label" for="field_id">ID</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="fields[]" value="name" id="field_name" checked><label class="form-check-label" for="field_name">Name</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="fields[]" value="price" id="field_price"><label class="form-check-label" for="field_price">Price</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="fields[]" value="sale_price" id="field_sale_price"><label class="form-check-label" for="field_sale_price">Sale Price</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="fields[]" value="stock_quantity" id="field_stock_quantity"><label class="form-check-label" for="field_stock_quantity">Stock</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="fields[]" value="sku" id="field_sku"><label class="form-check-label" for="field_sku">SKU</label></div>
                            </div>
                            <div class="col-6">
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="fields[]" value="status" id="field_status"><label class="form-check-label" for="field_status">Status</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="fields[]" value="category" id="field_category"><label class="form-check-label" for="field_category">Category</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="fields[]" value="brand" id="field_brand"><label class="form-check-label" for="field_brand">Brand</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="fields[]" value="main_image" id="field_main_image"><label class="form-check-label" for="field_main_image">Image</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="fields[]" value="created_at" id="field_created_at"><label class="form-check-label" for="field_created_at">Created At</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="fields[]" value="updated_at" id="field_updated_at"><label class="form-check-label" for="field_updated_at">Updated At</label></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="card shadow mb-4">
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="form-row align-items-end">
                <div class="form-group col-md-3">
                    <label for="filter_name">Name</label>
                    <input type="text" name="name" id="filter_name" value="{{ request('name') }}" class="form-control" placeholder="Search by name">
                </div>
                <div class="form-group col-md-2">
                    <label for="filter_category">Category</label>
                    <select name="category_id" id="filter_category" class="form-control">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="filter_brand">Brand</label>
                    <select name="brand_id" id="filter_brand" class="form-control">
                        <option value="">All Brands</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="filter_status">Status</label>
                    <select name="status" id="filter_status" class="form-control">
                        <option value="">All</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="filter_stock">Stock Status</label>
                    <select name="stock_status" id="filter_stock" class="form-control">
                        <option value="">All</option>
                        <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                        <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>
            </div>
            <div class="form-row align-items-end">
                <div class="form-group col-md-2">
                    <label for="filter_price_min">Min Price</label>
                    <input type="number" step="0.01" name="price_min" id="filter_price_min" value="{{ request('price_min') }}" class="form-control" placeholder="Min">
                </div>
                <div class="form-group col-md-2">
                    <label for="filter_price_max">Max Price</label>
                    <input type="number" step="0.01" name="price_max" id="filter_price_max" value="{{ request('price_max') }}" class="form-control" placeholder="Max">
                </div>
                <div class="form-group col-md-3">
                    <label for="filter_created_from">Created From</label>
                    <input type="date" name="created_from" id="filter_created_from" value="{{ request('created_from') }}" class="form-control">
                </div>
                <div class="form-group col-md-3">
                    <label for="filter_created_to">Created To</label>
                    <input type="date" name="created_to" id="filter_created_to" value="{{ request('created_to') }}" class="form-control">
                </div>
                <div class="form-group col-md-2">
                    <button type="submit" class="btn btn-info btn-block mt-4">Filter</button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Sale Price</th>
                        <th>Stock (Current)</th>
                        <th>SKU</th>
                        <th>Status</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>
                                @if($product->main_image)
                                    <img src="{{ asset('storage/' . $product->main_image) }}" alt="Image" style="max-width: 60px; max-height: 60px;" class="img-thumbnail">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->sale_price }}</td>
                            <td>
                                @php $currentStock = $product->current_stock; @endphp
                                <span class="{{ $currentStock <= 0 ? 'badge badge-danger' : ($currentStock < 5 ? 'badge badge-warning' : 'badge badge-success') }}" style="font-size: 0.95em; font-weight: 600; padding: 0.4em 0.8em; letter-spacing: 1px;">
                                    {{ number_format($currentStock) }}
                                </span>
                            </td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ ucfirst($product->status) }}</td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>{{ $product->brand->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-info" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3 d-flex justify-content-center">
            <nav>
                {{ $products->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    </div>
</div>
@endsection
