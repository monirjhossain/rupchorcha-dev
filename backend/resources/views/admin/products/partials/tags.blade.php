<div class="form-group">
    <label for="tags">Tags</label>
    <select name="tags[]" id="tags" class="form-control select2-tags" multiple="multiple">
        @foreach($tags as $tag)
            <option value="{{ $tag->id }}" {{ (isset($product) && $product->tags->contains($tag->id)) ? 'selected' : '' }}>{{ $tag->name }}</option>
        @endforeach
    </select>
    <small class="form-text text-muted">You can search and select multiple tags.</small>
</div>
