<?php
namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index() {
        $warehouses = Warehouse::paginate(20);
        return view('admin.warehouses.index', compact('warehouses'));
    }
    public function create() {
        return view('admin.warehouses.create');
    }
    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'manager' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
        ]);
        Warehouse::create($data);
        return redirect()->route('warehouses.index')->with('success', 'Warehouse created.');
    }
    public function edit($id) {
        $warehouse = Warehouse::findOrFail($id);
        return view('admin.warehouses.edit', compact('warehouse'));
    }
    public function update(Request $request, $id) {
        $warehouse = Warehouse::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'manager' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
        ]);
        $warehouse->update($data);
        return redirect()->route('warehouses.index')->with('success', 'Warehouse updated.');
    }
    public function destroy($id) {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->delete();
        return redirect()->route('warehouses.index')->with('success', 'Warehouse deleted.');
    }
}
