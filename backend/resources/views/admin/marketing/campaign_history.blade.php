@extends('layouts.admin')

@section('title', 'Campaign History')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Campaign History</h2>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light"><strong>Monthly Campaign Stats (Last 12 Months)</strong></div>
                <div class="card-body p-2">
                    <table class="table table-sm table-bordered mb-0">
                        <thead><tr><th>Month</th><th>Campaigns</th><th>Recipients</th></tr></thead>
                        <tbody>
                        @foreach($monthlyStats as $stat)
                            <tr>
                                <td>{{ date('F Y', mktime(0,0,0,$stat->month,1,$stat->year)) }}</td>
                                <td>{{ $stat->total }}</td>
                                <td>{{ $stat->recipients }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light"><strong>Admin Campaign Stats (All Time)</strong></div>
                <div class="card-body p-2">
                    <table class="table table-sm table-bordered mb-0">
                        <thead><tr><th>Admin</th><th>Campaigns</th><th>Recipients</th></tr></thead>
                        <tbody>
                        @foreach($userStats as $stat)
                            <tr>
                                <td>{{ $stat->admin->name ?? 'N/A' }}</td>
                                <td>{{ $stat->total }}</td>
                                <td>{{ $stat->recipients }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <form method="GET" class="row mb-3">
        <div class="col-md-2">
            <select name="month" class="form-control">
                <option value="">All Months</option>
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" @if(request('month') == $m) selected @endif>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="year" class="form-control">
                <option value="">All Years</option>
                @for($y = date('Y'); $y >= date('Y')-5; $y--)
                    <option value="{{ $y }}" @if(request('year') == $y) selected @endif>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-3">
            <select name="admin_id" class="form-control">
                <option value="">All Admins</option>
                @foreach($admins as $admin)
                    <option value="{{ $admin->id }}" @if(request('admin_id') == $admin->id) selected @endif>{{ $admin->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Date</th>
                    <th>Admin</th>
                    <th>Type</th>
                    <th>Subject</th>
                    <th>Recipients</th>
                </tr>
            </thead>
            <tbody>
                @forelse($campaigns as $campaign)
                <tr>
                    <td>{{ $campaign->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $campaign->admin->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($campaign->type) }}</td>
                    <td>{{ $campaign->subject }}</td>
                    <td>{{ $campaign->recipient_count }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">No campaigns found.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $campaigns->withQueryString()->links() }}
    </div>
</div>
@endsection
