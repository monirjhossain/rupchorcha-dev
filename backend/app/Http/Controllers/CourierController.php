<?php
namespace App\Http\Controllers;

use App\Models\Courier;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    public function index()
    {
        $couriers = Courier::orderBy('name')->get();
        return view('admin.couriers.index', compact('couriers'));
    }

    public function create()
    {
        return view('admin.couriers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'api_key' => 'nullable|string',
            'contact_number' => 'nullable|string',
            'email' => 'nullable|email',
            'tracking_url' => 'nullable|url',
            'status' => 'boolean',
            'logo' => 'nullable|image|max:2048',
            'service_area' => 'nullable|string',
            'delivery_types' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('courier_logos', 'public');
        }
        Courier::create($data);
        return redirect()->route('couriers.index')->with('success', 'Courier added!');
    }

    public function edit($id)
    {
        $courier = Courier::findOrFail($id);
        return view('admin.couriers.edit', compact('courier'));
    }

    public function update(Request $request, $id)
    {
        $courier = Courier::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'api_key' => 'nullable|string',
            'contact_number' => 'nullable|string',
            'email' => 'nullable|email',
            'tracking_url' => 'nullable|url',
            'status' => 'boolean',
            'logo' => 'nullable|image|max:2048',
            'service_area' => 'nullable|string',
            'delivery_types' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('courier_logos', 'public');
        }
        $courier->update($data);
        return redirect()->route('couriers.index')->with('success', 'Courier updated!');
    }

    public function destroy($id)
    {
        Courier::findOrFail($id)->delete();
        return redirect()->route('couriers.index')->with('success', 'Courier deleted!');
    }
}
