@extends('layouts.admin')
@section('title', 'User Activity Log')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Activity Log: {{ $user->name }}</h1>
    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>IP Address</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->activityLogs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>{{ ucfirst($log->type) }}</td>
                            <td>{{ $log->description }}</td>
                            <td>{{ $log->ip_address }}</td>
                            <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
