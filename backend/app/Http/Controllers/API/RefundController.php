<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Refund;

class RefundController extends Controller
{
    public function index()
    {
        return Refund::all();
    }

    public function show($id)
    {
        return Refund::findOrFail($id);
    }

    public function store(Request $request)
    {
        $refund = Refund::create($request->all());
        return response()->json($refund, 201);
    }

    public function update(Request $request, $id)
    {
        $refund = Refund::findOrFail($id);
        $refund->update($request->all());
        return response()->json($refund);
    }

    public function destroy($id)
    {
        Refund::destroy($id);
        return response()->json(null, 204);
    }
}
