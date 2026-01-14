<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }
    public function sales()
    {
        // Sales per month for the last 12 months
        $sales = \App\Models\Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total_orders, SUM(total) as total_sales')
            ->groupBy('month')
            ->orderBy('month')
            ->take(12)
            ->get();
        $labels = $sales->pluck('month');
        $data = $sales->pluck('total_sales');
        return view('admin.reports.sales', compact('labels', 'data'));
    }
    public function exportSales($type)
    {
        // TODO: Implement export logic for sales (CSV, Excel, PDF)
        return Response::make('Export sales as ' . $type);
    }
    public function revenue()
    {
        // Revenue per month for the last 12 months
        $revenue = \App\Models\Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total) as total_revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->take(12)
            ->get();
        $labels = $revenue->pluck('month');
        $data = $revenue->pluck('total_revenue');
        return view('admin.reports.revenue', compact('labels', 'data'));
    }
    public function exportRevenue($type)
    {
        // TODO: Implement export logic for revenue (CSV, Excel, PDF)
        return Response::make('Export revenue as ' . $type);
    }
    public function topProducts()
    {
        // Top 10 products by sales quantity
        $topProducts = \App\Models\OrderItem::selectRaw('product_id, SUM(quantity) as total_quantity')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->with('product')
            ->take(10)
            ->get();
        $labels = $topProducts->map(fn($item) => $item->product ? $item->product->name : 'Unknown');
        $data = $topProducts->pluck('total_quantity');
        return view('admin.reports.top_products', compact('labels', 'data'));
    }
    public function exportTopProducts($type)
    {
        // TODO: Implement export logic for top products (CSV, Excel, PDF)
        return Response::make('Export top products as ' . $type);
    }
    public function customers()
    {
        // Top 10 customers by order count
        $topCustomers = \App\Models\User::withCount('orders')
            ->orderByDesc('orders_count')
            ->take(10)
            ->get();
        $labels = $topCustomers->pluck('name');
        $data = $topCustomers->pluck('orders_count');
        return view('admin.reports.customers', compact('labels', 'data'));
    }
    public function exportCustomers($type)
    {
        // TODO: Implement export logic for customers (CSV, Excel, PDF)
        return Response::make('Export customers as ' . $type);
    }
}
