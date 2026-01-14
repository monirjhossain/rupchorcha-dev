@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Customer Analytics</h1>
    <div class="card mb-4">
        <div class="card-body">
            <canvas id="customersChart" height="100"></canvas>
        </div>
    </div>
    <a href="{{ route('reports.customers.export', ['type' => 'csv']) }}" class="btn btn-success mr-2">Export CSV</a>
    <a href="{{ route('reports.customers.export', ['type' => 'excel']) }}" class="btn btn-primary mr-2">Export Excel</a>
    <a href="{{ route('reports.customers.export', ['type' => 'pdf']) }}" class="btn btn-danger">Export PDF</a>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('customersChart').getContext('2d');
const customersChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: @json($labels),
        datasets: [{
            label: 'Customers',
            data: @json($data),
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true
    }
});
</script>
@endpush
