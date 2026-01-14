<?php
namespace App\Http\Controllers\API;

use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttributeController extends Controller
{
    // List all attributes
    public function index()
    {
        $attributes = Attribute::all();
        return response()->json(['success' => true, 'attributes' => $attributes]);
    }

    // Show attribute details
    public function show($id)
    {
        $attribute = Attribute::findOrFail($id);
        return response()->json(['success' => true, 'attribute' => $attribute]);
    }

    // Create attribute (admin)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $attribute = Attribute::create($request->all());
        return response()->json(['success' => true, 'message' => 'Attribute added.', 'attribute' => $attribute]);
    }

    // Update attribute (admin)
    public function update(Request $request, $id)
    {
        $attribute = Attribute::findOrFail($id);
        $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
        ]);
        $attribute->update($request->all());
        return response()->json(['success' => true, 'message' => 'Attribute updated.', 'attribute' => $attribute]);
    }

    // Delete attribute (admin)
    public function destroy($id)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->delete();
        return response()->json(['success' => true, 'message' => 'Attribute deleted.']);
    }
}
