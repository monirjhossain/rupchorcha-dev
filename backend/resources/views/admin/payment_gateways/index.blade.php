@extends('layouts.admin')

@section('title', 'Payment Gateways')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Payment Gateways</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gateways as $gateway)
                        <tr>
                            <td>{{ $gateway->name }}</td>
                            <td>{{ $gateway->slug }}</td>
                            <td>
                                @if($gateway->active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $gateway->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
