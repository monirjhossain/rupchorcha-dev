@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Attribute</h1>
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Attribute</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('attributes.update', $attribute->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" id="name" value="{{ $attribute->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="text" {{ $attribute->type == 'text' ? 'selected' : '' }}>Text</option>
                                <option value="select" {{ $attribute->type == 'select' ? 'selected' : '' }}>Select</option>
                                <option value="number" {{ $attribute->type == 'number' ? 'selected' : '' }}>Number</option>
                                <option value="color" {{ $attribute->type == 'color' ? 'selected' : '' }}>Color</option>
                                <option value="date" {{ $attribute->type == 'date' ? 'selected' : '' }}>Date</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('attributes.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
