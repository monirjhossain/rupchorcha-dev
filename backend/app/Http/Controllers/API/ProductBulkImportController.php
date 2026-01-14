<?php
namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductBulkImportController extends Controller
{
    // Bulk import products from CSV
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);
        $file = $request->file('file');
        $imported = 0;
        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ',');
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $data = array_combine($header, $row);
                $validator = Validator::make($data, [
                    'name' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'price' => 'required|numeric',
                    'stock' => 'required|integer',
                    'category_id' => 'required|integer|exists:categories,id',
                    'brand_id' => 'nullable|integer|exists:brands,id',
                ]);
                if ($validator->fails()) {
                    continue;
                }
                Product::create($validator->validated());
                $imported++;
            }
            fclose($handle);
        }
        return response()->json(['success' => true, 'message' => 'Products imported successfully.', 'imported_count' => $imported]);
    }
}
