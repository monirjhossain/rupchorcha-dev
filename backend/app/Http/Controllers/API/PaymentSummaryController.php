<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentSummary;

class PaymentSummaryController extends Controller
{
    public function index()
    {
        return PaymentSummary::all();
    }

    public function show($id)
    {
        return PaymentSummary::findOrFail($id);
    }

    public function store(Request $request)
    {
        $paymentSummary = PaymentSummary::create($request->all());
        return response()->json($paymentSummary, 201);
    }

    public function update(Request $request, $id)
    {
        $paymentSummary = PaymentSummary::findOrFail($id);
        $paymentSummary->update($request->all());
        return response()->json($paymentSummary);
    }

    public function destroy($id)
    {
        PaymentSummary::destroy($id);
        return response()->json(null, 204);
    }
}
