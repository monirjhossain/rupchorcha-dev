@extends('layouts.admin')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Categories</h1>
    <a href="{{ route('categories.create') }}" class="btn btn-success">+ Add Category</a>
</div>
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Parent</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        function renderCategoryRow($category, $level = 0) {
                            echo '<tr>';
                            echo '<td>' . $category->id . '</td>';
                            echo '<td>' . str_repeat('&mdash; ', $level) . e($category->name) . '</td>';
                            echo '<td>' . e($category->slug) . '</td>';
                            echo '<td>' . ($category->parent ? e($category->parent->name) : '-') . '</td>';
                            echo '<td>' . e($category->description) . '</td>';
                            echo '<td>';
                            echo '<a href="' . route('categories.edit', $category->id) . '" class="btn btn-sm btn-info">Edit</a> ';
                            echo '<form action="' . route('categories.destroy', $category->id) . '" method="POST" style="display:inline-block;" onsubmit="return confirm(\'Are you sure?\');">';
                            echo csrf_field();
                            echo method_field('DELETE');
                            echo '<button type="submit" class="btn btn-sm btn-danger">Delete</button>';
                            echo '</form>';
                            echo '</td>';
                            echo '</tr>';
                            foreach ($category->children as $child) {
                                renderCategoryRow($child, $level + 1);
                            }
                        }
                        $rootCategories = $categories->where('parent_id', null);
                    @endphp
                    @foreach($rootCategories as $category)
                        @php renderCategoryRow($category); @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
