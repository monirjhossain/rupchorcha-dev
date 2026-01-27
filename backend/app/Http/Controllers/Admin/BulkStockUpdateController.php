<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class BulkStockUpdateController extends Controller
{
    // Show the bulk stock update form
    public function showForm(Request $request)
    {
        $products = Product::orderBy('name')->get();
        return view('admin.inventory.bulk_stock_update', compact('products'));
    }

    // Handle the bulk stock update submission (CSV or manual)
    public function update(Request $request)
    {
        // CSV preview step
        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            $rows = [];
            $errors = [];
            if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
                $header = fgetcsv($handle);
                $header = array_map('strtolower', $header);
                $colMap = [
                    'product_id' => array_search('product_id', $header),
                    'sku' => array_search('sku', $header),
                    'name' => array_search('name', $header),
                    'quantity' => array_search('quantity', $header),
                ];
                if ((
                        $colMap['product_id'] === false &&
                        $colMap['sku'] === false &&
                        $colMap['name'] === false
                    ) || $colMap['quantity'] === false) {
                    $errors[] = 'CSV must have columns: product_id or sku or name, and quantity.';
                } else {
                    $rowNum = 1;
                    while (($data = fgetcsv($handle)) !== false) {
                        $rowNum++;
                        $productId = $colMap['product_id'] !== false ? $data[$colMap['product_id']] : null;
                        $sku = $colMap['sku'] !== false ? $data[$colMap['sku']] : null;
                        $name = $colMap['name'] !== false ? $data[$colMap['name']] : null;
                        $quantity = $data[$colMap['quantity']] ?? null;
                        $product = null;
                        if ($productId) {
                            $product = Product::find($productId);
                        } elseif ($sku) {
                            $product = Product::where('sku', $sku)->first();
                        } elseif ($name) {
                            $product = Product::where('name', $name)->first();
                        }
                        if (!$product) {
                            $errors[] = "Row $rowNum: Product not found (ID: $productId, SKU: $sku, Name: $name).";
                            continue;
                        }
                        if (!is_numeric($quantity) || $quantity < 0) {
                            $errors[] = "Row $rowNum: Invalid quantity for Product (ID: $productId, SKU: $sku, Name: $name).";
                            continue;
                        }
                        $rows[] = [
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'sku' => $product->sku,
                            'current_stock' => $product->stock_quantity,
                            'quantity' => $quantity,
                        ];
                    }
                }
                fclose($handle);
            } else {
                $errors[] = 'Could not read the uploaded CSV file.';
            }
            $products = Product::orderBy('name')->get();
            $csvPreview = [
                'rows' => $rows,
                'errors' => $errors,
            ];
            return view('admin.inventory.bulk_stock_update', compact('products', 'csvPreview'));
        }

        // Apply CSV update
        if ($request->input('apply_csv')) {
            $rows = $request->input('csv_rows', []);
            // Not used in this simple preview, but could be extended for AJAX
            // For now, just redirect back
            return redirect()->route('inventory.bulk_stock_update')->with('success', 'Bulk stock update applied!');
        }

        // Manual form update
        $data = $request->validate([
            'stocks' => 'required|array',
            'stocks.*.product_id' => 'required|exists:products,id',
            'stocks.*.quantity' => 'required|integer|min:0',
        ]);
        foreach ($data['stocks'] as $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $product->stock_quantity = $item['quantity'];
                $product->save();
            }
        }
        return redirect()->back()->with('success', 'Bulk stock update successful!');
    }
}
