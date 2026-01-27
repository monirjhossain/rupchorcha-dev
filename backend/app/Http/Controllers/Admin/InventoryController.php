<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\StockMovement;

use PDF;
use App\Models\User;



class InventoryController extends Controller
{

    /**
     * Export Stock Aging Report to CSV (Excel)
     */
    public function exportStockAgingReport(Request $request)
    {
        $query = Product::with(['brand', 'categories', 'stockMovements' => function($q) {
            $q->orderBy('created_at', 'desc');
        }]);
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $products = $query->get();
        $now = now();
        $filename = 'stock_aging_report_' . date('Ymd_His') . '.csv';
        $handle = fopen('php://memory', 'w');
        // Header
        fputcsv($handle, ['Product', 'SKU', 'Brand', 'Category', 'Stock Qty', 'Last Movement', 'Days in Stock', 'Aging Bucket']);
        foreach ($products as $product) {
            $lastMovement = $product->stockMovements->first();
            $lastMovementDate = $lastMovement ? $lastMovement->created_at : $product->created_at;
            $daysInStock = $lastMovementDate ? $now->diffInDays($lastMovementDate) : null;
            if ($daysInStock === null) {
                $bucket = 'Unknown';
            } elseif ($daysInStock <= 30) {
                $bucket = '0-30 days';
            } elseif ($daysInStock <= 60) {
                $bucket = '31-60 days';
            } elseif ($daysInStock <= 90) {
                $bucket = '61-90 days';
            } elseif ($daysInStock <= 180) {
                $bucket = '91-180 days';
            } else {
                $bucket = '181+ days';
            }
            fputcsv($handle, [
                $product->name,
                $product->sku,
                $product->brand->name ?? '',
                $product->categories->pluck('name')->join(', '),
                $product->stock_quantity,
                $lastMovementDate ? $lastMovementDate->format('Y-m-d') : 'N/A',
                $daysInStock ?? 'N/A',
                $bucket,
            ]);
        }
        fseek($handle, 0);
        return response()->streamDownload(function() use ($handle) {
            fpassthru($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Print-friendly Stock Aging Report
     */
    public function printStockAgingReport(Request $request)
    {
        $brands = Brand::all();
        $categories = Category::all();
        $query = Product::with(['brand', 'categories', 'stockMovements' => function($q) {
            $q->orderBy('created_at', 'desc');
        }]);
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $products = $query->get();
        $now = now();
        $agingData = $products->map(function($product) use ($now) {
            $lastMovement = $product->stockMovements->first();
            $lastMovementDate = $lastMovement ? $lastMovement->created_at : $product->created_at;
            $daysInStock = $lastMovementDate ? $now->diffInDays($lastMovementDate) : null;
            if ($daysInStock === null) {
                $bucket = 'Unknown';
            } elseif ($daysInStock <= 30) {
                $bucket = '0-30 days';
            } elseif ($daysInStock <= 60) {
                $bucket = '31-60 days';
            } elseif ($daysInStock <= 90) {
                $bucket = '61-90 days';
            } elseif ($daysInStock <= 180) {
                $bucket = '91-180 days';
            } else {
                $bucket = '181+ days';
            }
            return [
                'product' => $product,
                'last_movement_date' => $lastMovementDate,
                'days_in_stock' => $daysInStock,
                'aging_bucket' => $bucket,
            ];
        });
        return view('admin.inventory.print_stock_aging_report', [
            'agingData' => $agingData,
            'brands' => $brands,
            'categories' => $categories,
        ]);
    }

    /**
     * Advanced Stock Aging Report
     */
    public function stockAgingReport(Request $request)
    {
        $brands = Brand::all();
        $categories = Category::all();
        $query = Product::with(['brand', 'categories', 'stockMovements' => function($q) {
            $q->orderBy('created_at', 'desc');
        }]);

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->get();
        $now = now();
        $agingData = $products->map(function($product) use ($now) {
            $lastMovement = $product->stockMovements->first();
            $lastMovementDate = $lastMovement ? $lastMovement->created_at : $product->created_at;
            $daysInStock = $lastMovementDate ? $now->diffInDays($lastMovementDate) : null;
            // Aging buckets: 0-30, 31-60, 61-90, 91-180, 181+
            if ($daysInStock === null) {
                $bucket = 'Unknown';
            } elseif ($daysInStock <= 30) {
                $bucket = '0-30 days';
            } elseif ($daysInStock <= 60) {
                $bucket = '31-60 days';
            } elseif ($daysInStock <= 90) {
                $bucket = '61-90 days';
            } elseif ($daysInStock <= 180) {
                $bucket = '91-180 days';
            } else {
                $bucket = '181+ days';
            }
            return [
                'product' => $product,
                'last_movement_date' => $lastMovementDate,
                'days_in_stock' => $daysInStock,
                'aging_bucket' => $bucket,
            ];
        });
        return view('admin.inventory.stock_aging_report', [
            'agingData' => $agingData,
            'brands' => $brands,
            'categories' => $categories,
        ]);
    }

    /**
     * Download Inventory Stock Report as PDF
     */
    public function downloadStockReportPdf(Request $request)
    {
        // Ensure dompdf is installed: composer require barryvdh/laravel-dompdf
        $query = Product::with(['brand', 'categories']);
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $products = $query->get();

        $pdf = \PDF::loadView('admin.inventory.print_stock_report', compact('products'));
        return $pdf->download('inventory_stock_report.pdf');
    }

    public function printStockReport(Request $request)
    {
        $query = Product::with(['brand', 'categories']);
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $products = $query->get();
        return view('admin.inventory.print_stock_report', compact('products'));
    }
    public function stockReport(Request $request)
    {
        $brands = Brand::all();
        $categories = Category::all();
        $query = Product::with(['brand', 'categories']);

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->get();
        return view('admin.inventory.stock_report', compact('products', 'brands', 'categories'));
    }

    public function exportStockReport(Request $request)
    {
        $query = Product::with(['brand', 'categories']);
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $products = $query->get();
        $filename = 'stock_report_' . date('Ymd_His') . '.csv';
        $handle = fopen('php://memory', 'w');
        // Header
        fputcsv($handle, ['Product', 'SKU', 'Brand', 'Category', 'Stock Qty', 'Cost Price', 'Stock Value (Cost)', 'Sale Price', 'Stock Value (Sale)']);
        foreach ($products as $product) {
            fputcsv($handle, [
                $product->name,
                $product->sku,
                $product->brand->name ?? '',
                $product->categories->pluck('name')->join(', '),
                $product->stock_quantity,
                $product->cost_price,
                number_format($product->stock_quantity * ($product->cost_price ?? 0), 2),
                $product->sale_price,
                number_format($product->stock_quantity * ($product->sale_price ?? 0), 2),
            ]);
        }
        fseek($handle, 0);
        return response()->streamDownload(function() use ($handle) {
            fpassthru($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function stockMovementHistory(Request $request)
    {
        $products = Product::all();
        $users = User::all();
        $query = StockMovement::with(['product', 'user']);
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        $movements = $query->orderBy('created_at', 'desc')->paginate(30)->appends($request->except('page'));
        return view('admin.inventory.stock_movement_history', compact('movements', 'products', 'users'));
    }

    public function lowStockAlerts(Request $request)
    {
        $brands = Brand::all();
        $categories = Category::all();
        $threshold = $request->input('threshold', 5); // Default threshold
        $query = Product::with(['brand', 'categories'])
            ->where('stock_quantity', '<', $threshold);
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $products = $query->get();
        return view('admin.inventory.low_stock_alerts', compact('products', 'brands', 'categories', 'threshold'));
    }
}
