<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\AddressResource;

class AddressController extends Controller
{
    /**
     * Get all addresses for the authenticated user
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $addresses = Address::where('user_id', $user->id)->get();
        return response()->json(['success' => true, 'data' => AddressResource::collection($addresses)]);
    }

    /**
     * Get a single address (security check added)
     */
    public function show(Request $request, $id)
    {
        $address = Address::where('id', $id)->where('user_id', $request->user()->id)->first();

        if (!$address) {
            return response()->json(['success' => false, 'message' => 'Address not found'], 404);
        }

        return response()->json(['success' => true, 'data' => new AddressResource($address)]);
    }

    /**
     * Create a new address
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:shipping,billing',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address_line1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $data = $request->all();
        $data['user_id'] = $user->id;
        
        // If this is set as default, unset other defaults
        if (!empty($data['is_default']) && $data['is_default']) {
            Address::where('user_id', $user->id)
                  ->where('type', $data['type'])
                  ->update(['is_default' => false]);
        }

        $address = Address::create($data);
        return response()->json(['success' => true, 'data' => new AddressResource($address)], 201);
    }

    /**
     * Update an address (security check added)
     */
    public function update(Request $request, $id)
    {
        $address = Address::where('id', $id)->where('user_id', $request->user()->id)->first();

        if (!$address) {
            return response()->json(['success' => false, 'message' => 'Address not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'sometimes|string|in:shipping,billing',
            'name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address_line1' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'sometimes|string|max:100',
            'is_default' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        // If this is set as default, unset other defaults
        if (!empty($data['is_default']) && $data['is_default']) {
            Address::where('user_id', $address->user_id)
                  ->where('type', $data['type'] ?? $address->type) // Use new type if provided, else existing
                  ->where('id', '!=', $id)
                  ->update(['is_default' => false]);
        }

        $address->update($data);
        return response()->json(['success' => true, 'data' => new AddressResource($address)]);
    }

    /**
     * Delete an address (security check added)
     */
    public function destroy(Request $request, $id)
    {
        $address = Address::where('id', $id)->where('user_id', $request->user()->id)->first();

        if (!$address) {
            return response()->json(['success' => false, 'message' => 'Address not found'], 404);
        }

        $address->delete();
        return response()->json(['success' => true, 'message' => 'Address deleted successfully'], 200);
    }
}
