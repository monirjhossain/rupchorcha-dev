<div class="form-row">
    <div class="form-group col-md-6 mb-3">
        <label for="code">Coupon Code <span class="text-danger">*</span></label>
        <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $coupon->code ?? '') }}" required>
        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group col-md-6 mb-3">
        <label for="type">Type <span class="text-danger">*</span></label>
        <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
            <option value="fixed" {{ old('type', $coupon->type ?? '') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
            <option value="percent" {{ old('type', $coupon->type ?? '') == 'percent' ? 'selected' : '' }}>Percentage</option>
            <option value="free_shipping" {{ old('type', $coupon->type ?? '') == 'free_shipping' ? 'selected' : '' }}>Free Shipping</option>
        </select>
        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-4 mb-3">
        <label for="value">Value</label>
        <input type="number" step="0.01" name="value" id="value" class="form-control @error('value') is-invalid @enderror" value="{{ old('value', $coupon->value ?? '') }}">
        @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group col-md-4 mb-3">
        <label for="max_discount">Max Discount</label>
        <input type="number" step="0.01" name="max_discount" id="max_discount" class="form-control @error('max_discount') is-invalid @enderror" value="{{ old('max_discount', $coupon->max_discount ?? '') }}">
        @error('max_discount')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group col-md-4 mb-3">
        <label for="min_order_amount">Min Order Amount</label>
        <input type="number" step="0.01" name="min_order_amount" id="min_order_amount" class="form-control @error('min_order_amount') is-invalid @enderror" value="{{ old('min_order_amount', $coupon->min_order_amount ?? '') }}">
        @error('min_order_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-4 mb-3">
        <label for="usage_limit">Usage Limit</label>
        <input type="number" name="usage_limit" id="usage_limit" class="form-control @error('usage_limit') is-invalid @enderror" value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}">
        @error('usage_limit')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group col-md-4 mb-3">
        <label for="usage_limit_per_user">Usage Limit Per User</label>
        <input type="number" name="usage_limit_per_user" id="usage_limit_per_user" class="form-control @error('usage_limit_per_user') is-invalid @enderror" value="{{ old('usage_limit_per_user', $coupon->usage_limit_per_user ?? '') }}">
        @error('usage_limit_per_user')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group col-md-4 mb-3">
        <label for="start_at">Start Date</label>
        <input type="date" name="start_at" id="start_at" class="form-control @error('start_at') is-invalid @enderror" value="{{ old('start_at', isset($coupon->start_at) ? $coupon->start_at->format('Y-m-d') : '') }}">
        @error('start_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-4 mb-3">
        <label for="expires_at">Expiry Date</label>
        <input type="date" name="expires_at" id="expires_at" class="form-control @error('expires_at') is-invalid @enderror" value="{{ old('expires_at', isset($coupon->expires_at) ? $coupon->expires_at->format('Y-m-d') : '') }}">
        @error('expires_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group col-md-8 mb-3">
        <label for="product_ids">Restrict to Products</label>
        <select name="product_ids[]" id="product_ids" class="form-control select2-multiple" multiple="multiple">
            @php $selectedProducts = old('product_ids', isset($coupon->product_ids) ? $coupon->product_ids : []); @endphp
            @foreach(\App\Models\Product::orderBy('name')->get() as $product)
                <option value="{{ $product->id }}" {{ in_array($product->id, $selectedProducts ?? []) ? 'selected' : '' }}>{{ $product->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-6 mb-3">
        <label for="category_ids">Restrict to Categories</label>
        <select name="category_ids[]" id="category_ids" class="form-control select2-multiple" multiple="multiple">
            @php $selectedCategories = old('category_ids', isset($coupon->category_ids) ? $coupon->category_ids : []); @endphp
            @foreach(\App\Models\Category::orderBy('name')->get() as $category)
                <option value="{{ $category->id }}" {{ in_array($category->id, $selectedCategories ?? []) ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-6 mb-3">
        <label for="brand_ids">Restrict to Brands</label>
        <select name="brand_ids[]" id="brand_ids" class="form-control select2-multiple" multiple="multiple">
            @php $selectedBrands = old('brand_ids', isset($coupon->brand_ids) ? $coupon->brand_ids : []); @endphp
            @foreach(\App\Models\Brand::orderBy('name')->get() as $brand)
                <option value="{{ $brand->id }}" {{ in_array($brand->id, $selectedBrands ?? []) ? 'selected' : '' }}>{{ $brand->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-6 mb-3">
        <label for="user_ids">Restrict to Users</label>
        <input type="text" name="user_ids[]" id="user_ids" class="form-control" value="{{ old('user_ids', isset($coupon->user_ids) ? implode(',', $coupon->user_ids) : '') }}" placeholder="Comma separated user IDs">
    </div>
    <div class="form-group col-md-3 mb-3 d-flex align-items-center">
        <div class="form-check">
            <input type="checkbox" name="first_time_customer_only" id="first_time_customer_only" class="form-check-input" value="1" {{ old('first_time_customer_only', $coupon->first_time_customer_only ?? false) ? 'checked' : '' }}>
            <label for="first_time_customer_only" class="form-check-label ml-2">First Time Customer Only</label>
        </div>
    </div>
    <div class="form-group col-md-3 mb-3 d-flex align-items-center">
        <div class="form-check">
            <input type="checkbox" name="exclude_sale_items" id="exclude_sale_items" class="form-check-input" value="1" {{ old('exclude_sale_items', $coupon->exclude_sale_items ?? false) ? 'checked' : '' }}>
            <label for="exclude_sale_items" class="form-check-label ml-2">Exclude Sale Items</label>
        </div>
    </div>
</div>
