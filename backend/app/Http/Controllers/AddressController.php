<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addresses = Address::with('user')->latest()->paginate(20);
        return view('admin.addresses.index', compact('addresses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = \App\Models\User::all();
        return view('admin.addresses.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string',
            'name' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'address_line1' => 'required|string',
            'address_line2' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'country' => 'required|string',
            'is_default' => 'boolean',
        ]);
        Address::create($data);
        return redirect()->route('addresses.index')->with('success', 'Address created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Address $address)
    {
        $users = \App\Models\User::all();
        return view('admin.addresses.edit', compact('address', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Address $address)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string',
            'name' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'address_line1' => 'required|string',
            'address_line2' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'country' => 'required|string',
            'is_default' => 'boolean',
        ]);
        $address->update($data);
        return redirect()->route('addresses.index')->with('success', 'Address updated successfully.');
    }
        /**
     * AJAX: Return addresses for a user (for dynamic dropdown).
     */
    public function ajaxByUser(Request $request)
    {
        $userId = $request->input('user_id');
        if (!$userId) {
            return response()->json(['error' => 'User ID required'], 400);
        }
        $addresses = Address::where('user_id', $userId)->get();
        return response()->json($addresses);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        $address->delete();
        return redirect()->route('addresses.index')->with('success', 'Address deleted successfully.');
    }
        /**
     * Export all customer addresses as CSV.
     */
    public function export()
    {
        $addresses = Address::with('user')->get();
        $filename = 'customer_addresses_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        $columns = ['ID', 'User', 'Type', 'Name', 'Phone', 'Email', 'Address Line 1', 'Address Line 2', 'City', 'State', 'Postal Code', 'Country', 'Default', 'Created At'];
        $callback = function() use ($addresses, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($addresses as $address) {
                fputcsv($file, [
                    $address->id,
                    $address->user ? $address->user->name : '',
                    $address->type,
                    $address->name,
                    $address->phone,
                    $address->email,
                    $address->address_line1,
                    $address->address_line2,
                    $address->city,
                    $address->state,
                    $address->postal_code,
                    $address->country,
                    $address->is_default ? 'Yes' : 'No',
                    $address->created_at,
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
        /**
     * Handle bulk SMS sending to filtered customers.
     */
    public function bulk_sms(Request $request)
    {
        $request->validate([
            'sms_message' => 'required|string',
            'address_ids' => 'required|array',
        ]);

        $addresses = Address::whereIn('id', $request->address_ids)->whereNotNull('phone')->get();
        $count = 0;
        foreach ($addresses as $address) {
            // sendSms($address->phone, $request->sms_message); // Implement this
            $count++;
        }
        return redirect()->route('addresses.index')->with('success', "Bulk SMS sent to $count customers.");
    }
}
