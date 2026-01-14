<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
     // List all payment gateways
    public function gateways()
    {
        return response()->json(\App\Models\PaymentGateway::where('is_active', true)->get());
    }

    // Initiate a payment (API endpoint)
    public function initiate(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'payment_gateway_id' => 'required|exists:payment_gateways,id',
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Here, you would call the gateway API (Bkash, Nagad, SSLCommerz, etc.)
        // For now, just create a transaction record
        $transaction = \App\Models\Transaction::create([
            'order_id' => $request->order_id,
            'user_id' => $request->user()->id ?? null,
            'payment_gateway_id' => $request->payment_gateway_id,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'status' => 'pending',
            'payload' => null,
        ]);

        return response()->json(['transaction' => $transaction], 201);
    }

    // Handle payment gateway callback/webhook
    public function callback(Request $request, $gateway)
    {
        // This method should process the gateway response and update the transaction
        // Implementation will depend on the gateway
        return response()->json(['message' => 'Callback received for ' . $gateway]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
   
