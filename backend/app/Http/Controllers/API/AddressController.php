<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends Controller
{
    public function index()
    {
        return Address::all();
    }

    public function show($id)
    {
        return Address::findOrFail($id);
    }

    public function store(Request $request)
    {
        $address = Address::create($request->all());
        return response()->json($address, 201);
    }

    public function update(Request $request, $id)
    {
        $address = Address::findOrFail($id);
        $address->update($request->all());
        return response()->json($address);
    }

    public function destroy($id)
    {
        Address::destroy($id);
        return response()->json(null, 204);
    }
}
