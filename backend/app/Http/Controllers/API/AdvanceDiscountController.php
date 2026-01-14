<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdvanceDiscount;

class AdvanceDiscountController extends Controller
{
    public function index()
    {
        return AdvanceDiscount::all();
    }

    public function show($id)
    {
        return AdvanceDiscount::findOrFail($id);
    }

    public function store(Request $request)
    {
        $advanceDiscount = AdvanceDiscount::create($request->all());
        return response()->json($advanceDiscount, 201);
    }

    public function update(Request $request, $id)
    {
        $advanceDiscount = AdvanceDiscount::findOrFail($id);
        $advanceDiscount->update($request->all());
        return response()->json($advanceDiscount);
    }

    public function destroy($id)
    {
        AdvanceDiscount::destroy($id);
        return response()->json(null, 204);
    }
}
