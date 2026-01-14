<?php
namespace App\Http\Controllers\API;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    // List all suppliers
    public function index()
    {
        $suppliers = Supplier::all();
        return response()->json(['success' => true, 'suppliers' => $suppliers]);
    }

    // Show supplier details
    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        return response()->json(['success' => true, 'supplier' => $supplier]);
    }

    // Create supplier (admin)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string',
            'address' => 'nullable|string',
        ]);
        $supplier = Supplier::create($request->all());
        return response()->json(['success' => true, 'message' => 'Supplier added.', 'supplier' => $supplier]);
    }

    // Update supplier (admin)
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $request->validate([
            'name' => 'string|max:255',
            'contact' => 'nullable|string',
            'address' => 'nullable|string',
        ]);
        $supplier->update($request->all());
        return response()->json(['success' => true, 'message' => 'Supplier updated.', 'supplier' => $supplier]);
    }

    // Delete supplier (admin)
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return response()->json(['success' => true, 'message' => 'Supplier deleted.']);
    }
}
