<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockAdjustmentController extends Controller
{
    public function undo(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $lastAdjustment = StockMovement::where('product_id', $product->id)
            ->where('type', 'adjustment')
            ->orderByDesc('created_at')
            ->first();
        if (!$lastAdjustment) {
            return response()->json([
                'success' => false,
                'message' => 'No adjustment found to undo.'
            ]);
        }
        $product->stock_quantity -= $lastAdjustment->quantity;
        $product->save();
        // Log the undo as a new adjustment
        StockMovement::create([
            'product_id' => $product->id,
            'type' => 'adjustment',
            'quantity' => -$lastAdjustment->quantity,
            'reason' => 'Undo last adjustment',
            'user_id' => Auth::id(),
        ]);
        $lastAdjustment->delete();
        return response()->json([
            'success' => true,
            'message' => 'Last adjustment undone.'
        ]);
    }
    public function index(Request $request)
    {
        $products = Product::orderBy('name')->get();
        $csvPreview = $request->session()->get('csvPreview');
        return view('admin.inventory.stock_adjustment', compact('products', 'csvPreview'));
    }

    public function adjust(Request $request)
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
                    'type' => array_search('type', $header),
                    'quantity' => array_search('quantity', $header),
                    'reason' => array_search('reason', $header),
                ];
                if ((
                        $colMap['product_id'] === false &&
                        $colMap['sku'] === false &&
                        $colMap['name'] === false
                    ) || $colMap['type'] === false || $colMap['quantity'] === false) {
                    $errors[] = 'CSV must have columns: product_id or sku or name, type (increase|decrease|set), and quantity.';
                } else {
                    $rowNum = 1;
                    while (($data = fgetcsv($handle)) !== false) {
                        $rowNum++;
                        $productId = $colMap['product_id'] !== false ? $data[$colMap['product_id']] : null;
                        $sku = $colMap['sku'] !== false ? $data[$colMap['sku']] : null;
                        $name = $colMap['name'] !== false ? $data[$colMap['name']] : null;
                        $type = $colMap['type'] !== false ? strtolower($data[$colMap['type']]) : null;
                        $quantity = $data[$colMap['quantity']] ?? null;
                        $reason = $colMap['reason'] !== false ? $data[$colMap['reason']] : '';
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
                        if (!in_array($type, ['increase', 'decrease', 'set'])) {
                            $errors[] = "Row $rowNum: Invalid type for Product (ID: $productId, SKU: $sku, Name: $name).";
                            continue;
                        }
                        if (!is_numeric($quantity) || $quantity < 1) {
                            $errors[] = "Row $rowNum: Invalid quantity for Product (ID: $productId, SKU: $sku, Name: $name).";
                            continue;
                        }
                        $rows[] = [
                            'product_id' => $product->id,
                            'sku' => $product->sku,
                            'product_name' => $product->name,
                            'type' => $type,
                            'current_stock' => $product->stock_quantity,
                            'quantity' => $quantity,
                            'reason' => $reason,
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
            return view('admin.inventory.stock_adjustment', compact('products', 'csvPreview'));
        }

        // Apply CSV update
        if ($request->input('apply_csv')) {
            $isAjax = $request->ajax() || $request->wantsJson();
            $rows = json_decode($request->input('csv_rows', '[]'), true);
            $errors = [];
            $successCount = 0;
            foreach ($rows as $row) {
                $product = \App\Models\Product::find($row['product_id']);
                if (!$product) {
                    $errors[] = "Product not found: ID {$row['product_id']}";
                    continue;
                }
                $oldStock = $product->stock_quantity;
                $type = $row['type'];
                $quantity = (int) $row['quantity'];
                if ($type === 'increase') {
                    $product->stock_quantity += $quantity;
                } elseif ($type === 'decrease') {
                    $product->stock_quantity = max(0, $product->stock_quantity - $quantity);
                } elseif ($type === 'set') {
                    $product->stock_quantity = $quantity;
                } else {
                    $errors[] = "Invalid type for product ID {$row['product_id']}";
                    continue;
                }
                $product->save();
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'adjustment',
                    'quantity' => $product->stock_quantity - $oldStock,
                    'reason' => $row['reason'] ?? 'Bulk adjustment',
                    'user_id' => Auth::id(),
                ]);
                $successCount++;
            }
            if ($isAjax) {
                return response()->json([
                    'success' => count($errors) === 0,
                    'message' => $successCount . ' adjustments applied.' . (count($errors) ? ' Some errors occurred.' : ''),
                    'errors' => $errors,
                ]);
            }
            return redirect()->route('inventory.stock_adjustment')->with('success', $successCount . ' adjustments applied.' . (count($errors) ? ' Some errors occurred.' : ''));
        }

        // Manual form update
        $isAjax = $request->ajax() || $request->wantsJson();
        try {
            $data = $request->validate([
                'product_id' => 'required|exists:products,id',
                'type' => 'required|in:increase,decrease,set',
                'quantity' => 'required|integer|min:1',
                'reason' => 'nullable|string|max:255',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $e->errors(),
                ], 422);
            }
            throw $e;
        }
        $product = Product::findOrFail($data['product_id']);
        $oldStock = $product->stock_quantity;
        if ($data['type'] === 'increase') {
            $product->stock_quantity += $data['quantity'];
        } elseif ($data['type'] === 'decrease') {
            $product->stock_quantity = max(0, $product->stock_quantity - $data['quantity']);
        } elseif ($data['type'] === 'set') {
            $product->stock_quantity = $data['quantity'];
        }
        $product->save();
        // Log adjustment in StockMovement
        StockMovement::create([
            'product_id' => $product->id,
            'type' => 'adjustment',
            'quantity' => $product->stock_quantity - $oldStock,
            'reason' => $data['reason'] ?? 'Manual adjustment',
            'user_id' => Auth::id(),
        ]);
        if ($isAjax) {
            return response()->json([
                'success' => true,
                'message' => 'Stock adjusted successfully!'
            ]);
        }
        return redirect()->back()->with('success', 'Stock adjusted successfully!');
    }
}
