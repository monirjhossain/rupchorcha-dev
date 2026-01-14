<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;

class DashboardReportController extends Controller
{
    // Brand Wise Sales Report
    public function brandReport()
    {
        $brandSales = Brand::with(['products.orders' => function($q) {
            $q->where('status', 'completed');
        }])->get()->map(function($brand) {
            $total = $brand->products->flatMap->orders->sum('total');
            return [
                'brand' => $brand->name,
                'sales' => $total
            ];
        });
        return view('admin.report.brand', compact('brandSales'));
    }
      // Default index method for landing page or redirect
    public function index()
    {
        // You can customize this to show a dashboard or redirect
        return redirect()->route('report.brand');
    }

    public function exportBrandReport()
    {
        $brandSales = Brand::with(['products.orders' => function($q) {
            $q->where('status', 'completed');
        }])->get()->map(function($brand) {
            $total = $brand->products->flatMap->orders->sum('total');
            return [$brand->name, $total];
        });
        $filename = 'brand_sales_report_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename"
        ];
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['Brand', 'Sales']);
        foreach ($brandSales as $row) fputcsv($handle, $row);
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        return response($csv, 200, $headers);
    }

    // Category Wise Sales Report
    public function categoryReport()
    {
        $categorySales = Category::with(['products.orders' => function($q) {
            $q->where('status', 'completed');
        }])->get()->map(function($cat) {
            $total = $cat->products->flatMap->orders->sum('total');
            return [
                'category' => $cat->name,
                'sales' => $total
            ];
        });
        return view('admin.report.category', compact('categorySales'));
    }

    public function exportCategoryReport()
    {
        $categorySales = Category::with(['products.orders' => function($q) {
            $q->where('status', 'completed');
        }])->get()->map(function($cat) {
            $total = $cat->products->flatMap->orders->sum('total');
            return [$cat->name, $total];
        });
        $filename = 'category_sales_report_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename"
        ];
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['Category', 'Sales']);
        foreach ($categorySales as $row) fputcsv($handle, $row);
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        return response($csv, 200, $headers);
    }

    // Total Sales Report
    public function totalReport()
    {
        $totalSales = Order::where('status', 'completed')->sum('total');
        return view('admin.report.total', compact('totalSales'));
    }

    public function exportTotalReport()
    {
        $totalSales = Order::where('status', 'completed')->sum('total');
        $filename = 'total_sales_report_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename"
        ];
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['Total Sales']);
        fputcsv($handle, [$totalSales]);
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        return response($csv, 200, $headers);
    }

    // Max Selling Products Report
    public function maxProductsReport()
    {
        $maxProducts = Product::withCount(['orders' => function($q) {
            $q->where('status', 'completed');
        }])->orderByDesc('orders_count')->take(10)->get();
        return view('admin.report.max_products', compact('maxProducts'));
    }

    public function exportMaxProductsReport()
    {
        $maxProducts = Product::withCount(['orders' => function($q) {
            $q->where('status', 'completed');
        }])->orderByDesc('orders_count')->take(10)->get();
        $filename = 'max_products_report_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename"
        ];
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['Product', 'Orders']);
        foreach ($maxProducts as $product) fputcsv($handle, [$product->name, $product->orders_count]);
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        return response($csv, 200, $headers);
    }
    
}
