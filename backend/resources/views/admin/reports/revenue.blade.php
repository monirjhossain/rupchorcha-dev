@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Revenue Analytics</h1>
    <div class="card mb-4">
        <div class="card-body">
            <canvas id="revenueChart" height="100"></canvas>
        </div>
    </div>
    <a href="{{ route('reports.revenue.export', ['type' => 'csv']) }}" class="btn btn-success mr-2">Export CSV</a>
    <a href="{{ route('reports.revenue.export', ['type' => 'excel']) }}" class="btn btn-primary mr-2">Export Excel</a>
    <a href="{{ route('reports.revenue.export', ['type' => 'pdf']) }}" class="btn btn-danger">Export PDF</a>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($labels),
        datasets: [{
            label: 'Revenue',
            data: @json($data),
            backgroundColor: 'rgba(255, 206, 86, 0.2)',
            borderColor: 'rgba(255, 206, 86, 1)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
@endpush
