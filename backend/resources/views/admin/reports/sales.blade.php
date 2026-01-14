@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Sales Analytics</h1>
    <div class="card mb-4">
        <div class="card-body">
            <canvas id="salesChart" height="100"></canvas>
        </div>
    </div>
    <a href="{{ route('reports.sales.export', ['type' => 'csv']) }}" class="btn btn-success mr-2">Export CSV</a>
    <a href="{{ route('reports.sales.export', ['type' => 'excel']) }}" class="btn btn-primary mr-2">Export Excel</a>
    <a href="{{ route('reports.sales.export', ['type' => 'pdf']) }}" class="btn btn-danger">Export PDF</a>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($labels),
        datasets: [{
            label: 'Sales',
            data: @json($data),
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
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
