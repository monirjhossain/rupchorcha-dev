<div class="form-row">
    <div class="form-group col-md-6 mb-3">
        <label for="title">Title <span class="text-danger">*</span></label>
        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $discount->title ?? '') }}" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group col-md-6 mb-3">
        <label for="type">Discount Type <span class="text-danger">*</span></label>
        <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
            <option value="bogo" {{ old('type', $discount->type ?? '') == 'bogo' ? 'selected' : '' }}>Buy One Get One</option>
            <option value="combo" {{ old('type', $discount->type ?? '') == 'combo' ? 'selected' : '' }}>Combo Offer</option>
            <option value="category" {{ old('type', $discount->type ?? '') == 'category' ? 'selected' : '' }}>Category Wise</option>
            <option value="brand" {{ old('type', $discount->type ?? '') == 'brand' ? 'selected' : '' }}>Brand Wise</option>
            <option value="product" {{ old('type', $discount->type ?? '') == 'product' ? 'selected' : '' }}>Product Wise</option>
        </select>
        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-6 mb-3">
        <label for="discount_value">Discount Value</label>
        <input type="number" step="0.01" name="discount_value" id="discount_value" class="form-control @error('discount_value') is-invalid @enderror" value="{{ old('discount_value', $discount->discount_value ?? '') }}">
        @error('discount_value')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group col-md-6 mb-3">
        <label for="discount_type">Discount Unit</label>
        <select name="discount_type" id="discount_type" class="form-control @error('discount_type') is-invalid @enderror">
            <option value="percent" {{ old('discount_type', $discount->discount_type ?? '') == 'percent' ? 'selected' : '' }}>% (Percent)</option>
            <option value="fixed" {{ old('discount_type', $discount->discount_type ?? '') == 'fixed' ? 'selected' : '' }}>৳ (Fixed)</option>
        </select>
        @error('discount_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-4 mb-3">
        <label for="min_quantity">Min Quantity</label>
        <input type="number" name="min_quantity" id="min_quantity" class="form-control @error('min_quantity') is-invalid @enderror" value="{{ old('min_quantity', $discount->min_quantity ?? '') }}">
        @error('min_quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group col-md-4 mb-3">
        <label for="start_at">Start Date</label>
        <input type="date" name="start_at" id="start_at" class="form-control @error('start_at') is-invalid @enderror" value="{{ old('start_at', isset($discount->start_at) ? $discount->start_at->format('Y-m-d') : '') }}">
        @error('start_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group col-md-4 mb-3">
        <label for="expires_at">Expiry Date</label>
        <input type="date" name="expires_at" id="expires_at" class="form-control @error('expires_at') is-invalid @enderror" value="{{ old('expires_at', isset($discount->expires_at) ? $discount->expires_at->format('Y-m-d') : '') }}">
        @error('expires_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-12 mb-3">
        <label for="product_ids">Products (for Product/Combo/BOGO)</label>
        <select name="product_ids[]" id="product_ids" class="form-control select2-multiple" multiple="multiple">
            @foreach($products as $product)
                <option value="{{ $product->id }}" {{ in_array($product->id, old('product_ids', $discount->product_ids ?? [])) ? 'selected' : '' }}>{{ $product->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-12 mb-3">
        <label for="combo_product_ids">Combo Products (for Combo Offer)</label>
        <select name="combo_product_ids[]" id="combo_product_ids" class="form-control select2-multiple" multiple="multiple">
            @foreach($products as $product)
                <option value="{{ $product->id }}" {{ in_array($product->id, old('combo_product_ids', $discount->combo_product_ids ?? [])) ? 'selected' : '' }}>{{ $product->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-6 mb-3">
        <label for="category_ids">Categories</label>
        <select name="category_ids[]" id="category_ids" class="form-control select2-multiple" multiple="multiple">
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ in_array($category->id, old('category_ids', $discount->category_ids ?? [])) ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-6 mb-3">
        <label for="brand_ids">Brands</label>
        <select name="brand_ids[]" id="brand_ids" class="form-control select2-multiple" multiple="multiple">
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}" {{ in_array($brand->id, old('brand_ids', $discount->brand_ids ?? [])) ? 'selected' : '' }}>{{ $brand->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-3 mb-3 d-flex align-items-center">
        <div class="form-check">
            <input type="checkbox" name="active" id="active" class="form-check-input" value="1" {{ old('active', $discount->active ?? true) ? 'checked' : '' }}>
            <label for="active" class="form-check-label ml-2">Active</label>
        </div>
    </div>
</div>
