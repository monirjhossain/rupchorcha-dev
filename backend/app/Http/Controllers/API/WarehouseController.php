<?php
namespace App\Http\Controllers\API;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WarehouseController extends Controller
{
    // List all warehouses
    public function index()
    {
        $warehouses = Warehouse::all();
        return response()->json(['success' => true, 'warehouses' => $warehouses]);
    }

    // Show warehouse details
    public function show($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        return response()->json(['success' => true, 'warehouse' => $warehouse]);
    }

    // Create warehouse (admin)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string',
        ]);
        $warehouse = Warehouse::create($request->all());
        return response()->json(['success' => true, 'message' => 'Warehouse added.', 'warehouse' => $warehouse]);
    }

    // Update warehouse (admin)
    public function update(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $request->validate([
            'name' => 'string|max:255',
            'location' => 'nullable|string',
        ]);
        $warehouse->update($request->all());
        return response()->json(['success' => true, 'message' => 'Warehouse updated.', 'warehouse' => $warehouse]);
    }

    // Delete warehouse (admin)
    public function destroy($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->delete();
        return response()->json(['success' => true, 'message' => 'Warehouse deleted.']);
    }
}
