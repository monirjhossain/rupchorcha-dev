<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['order', 'user', 'paymentGateway'])->latest()->paginate(20);
        return view('admin.transactions.index', compact('transactions'));
    }
}
