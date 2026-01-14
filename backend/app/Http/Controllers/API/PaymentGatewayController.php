<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        return PaymentGateway::all();
    }

    public function show($id)
    {
        return PaymentGateway::findOrFail($id);
    }

    public function store(Request $request)
    {
        $paymentGateway = PaymentGateway::create($request->all());
        return response()->json($paymentGateway, 201);
    }

    public function update(Request $request, $id)
    {
        $paymentGateway = PaymentGateway::findOrFail($id);
        $paymentGateway->update($request->all());
        return response()->json($paymentGateway);
    }

    public function destroy($id)
    {
        PaymentGateway::destroy($id);
        return response()->json(null, 204);
    }
}
