<?php
namespace App\Http\Controllers\API;

use App\Models\Courier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourierController extends Controller
{
    // List all couriers
    public function index()
    {
        $couriers = Courier::all();
        return response()->json(['success' => true, 'couriers' => $couriers]);
    }

    // Show courier details
    public function show($id)
    {
        $courier = Courier::findOrFail($id);
        return response()->json(['success' => true, 'courier' => $courier]);
    }

    // Create courier (admin)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string',
            'tracking_url' => 'nullable|string',
        ]);
        $courier = Courier::create($request->all());
        return response()->json(['success' => true, 'message' => 'Courier added.', 'courier' => $courier]);
    }

    // Update courier (admin)
    public function update(Request $request, $id)
    {
        $courier = Courier::findOrFail($id);
        $request->validate([
            'name' => 'string|max:255',
            'contact' => 'nullable|string',
            'tracking_url' => 'nullable|string',
        ]);
        $courier->update($request->all());
        return response()->json(['success' => true, 'message' => 'Courier updated.', 'courier' => $courier]);
    }

    // Delete courier (admin)
    public function destroy($id)
    {
        $courier = Courier::findOrFail($id);
        $courier->delete();
        return response()->json(['success' => true, 'message' => 'Courier deleted.']);
    }
}
