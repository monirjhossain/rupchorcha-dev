<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PaymentSummaryController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();
        // Optional: filter by date
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }
        $paymentSummary = [
            'Bkash' => (clone $query)->where('payment_method', 'Bkash')->sum('total'),
            'Cash' => (clone $query)->where('payment_method', 'Cash')->sum('total'),
            'Nagad' => (clone $query)->where('payment_method', 'Nagad')->sum('total'),
            'Bank' => (clone $query)->where('payment_method', 'Bank')->sum('total'),
            'Master Card' => (clone $query)->where('payment_method', 'Master Card')->sum('total'),
        ];
        return view('admin.payments.summary', compact('paymentSummary'));
    }
}
