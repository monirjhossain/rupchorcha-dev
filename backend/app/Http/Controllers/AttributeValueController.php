<?php

namespace App\Http\Controllers;

use App\Models\AttributeValue;
use Illuminate\Http\Request;

class AttributeValueController extends Controller
{
    public function index() { $values = AttributeValue::with('attribute')->get(); return view('admin.attribute_values.index', compact('values')); }
    public function create() { return view('admin.attribute_values.create'); }
    public function store(Request $request) { $validated = $request->validate(['attribute_id'=>'required|exists:attributes,id','value'=>'required']); AttributeValue::create($validated); return redirect()->route('attribute_values.index')->with('success','Attribute value created.'); }
    public function edit($id) { $value = AttributeValue::findOrFail($id); return view('admin.attribute_values.edit', compact('value')); }
    public function update(Request $request, $id) { $value = AttributeValue::findOrFail($id); $validated = $request->validate(['attribute_id'=>'required|exists:attributes,id','value'=>'required']); $value->update($validated); return redirect()->route('attribute_values.index')->with('success','Attribute value updated.'); }
    public function destroy($id) { $value = AttributeValue::findOrFail($id); $value->delete(); return redirect()->route('attribute_values.index')->with('success','Attribute value deleted.'); }
}
