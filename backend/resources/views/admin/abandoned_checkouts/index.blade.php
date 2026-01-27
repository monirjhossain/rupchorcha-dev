@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <h2>Abandoned Checkouts</h2>
    <form method="get" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <input type="text" name="email" class="form-control" placeholder="Search by email" value="{{ request('email') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="abandoned" @if(request('status')=='abandoned') selected @endif>Abandoned</option>
                    <option value="recovered" @if(request('status')=='recovered') selected @endif>Recovered</option>
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Started At</th>
                <th>Last Activity</th>
                <th>Status</th>
                <th>Cart</th>
            </tr>
        </thead>
        <tbody>
        @foreach($abandoned as $row)
            <tr>
                <td>{{ $row->user ? $row->user->name : 'Guest' }}</td>
                <td>{{ $row->email }}</td>
                <td>{{ $row->started_at }}</td>
                <td>{{ $row->last_activity_at }}</td>
                <td>{{ ucfirst($row->status) }}</td>
                <td>
                    @foreach(($row->cart_data['items'] ?? []) as $item)
                        <div>{{ $item['product_name'] }} (x{{ $item['quantity'] }})</div>
                    @endforeach
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $abandoned->links() }}
</div>
@endsection
