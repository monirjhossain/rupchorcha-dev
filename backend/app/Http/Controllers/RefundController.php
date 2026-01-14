<?php

namespace App\Http\Controllers;

use App\Models\Refund;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function edit($id)
    {
        $refund = Refund::findOrFail($id);
        return view('admin.refunds.edit', compact('refund'));
    }

    public function update(Request $request, $id)
    {
        $refund = Refund::findOrFail($id);
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'user_id' => 'nullable|exists:users,id',
            'transaction_id' => 'nullable|exists:transactions,id',
            'type' => 'required|in:refund,return,exchange',
            'amount' => 'nullable|numeric',
            'reason' => 'nullable|string',
            'details' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected,completed',
        ]);
        $refund->update($data);
        return redirect()->route('refunds.show', $refund->id)->with('success', 'Request updated successfully.');
    }

    public function destroy($id)
    {
        $refund = Refund::findOrFail($id);
        $refund->delete();
        return redirect()->route('refunds.index')->with('success', 'Request deleted successfully.');
    }

    public function create()
    {
        return view('admin.refunds.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'user_id' => 'nullable|exists:users,id',
            'transaction_id' => 'nullable|exists:transactions,id',
            'type' => 'required|in:refund,return,exchange',
            'amount' => 'nullable|numeric',
            'reason' => 'nullable|string',
            'details' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected,completed',
        ]);
        Refund::create($data);
        return redirect()->route('refunds.index')->with('success', 'Request created successfully.');
    }

    public function index()
    {
        $refunds = Refund::with(['order', 'user', 'transaction'])->latest()->paginate(20);
        return view('admin.refunds.index', compact('refunds'));
    }

    public function show($id)
    {
        $refund = Refund::with(['order', 'user', 'transaction'])->findOrFail($id);
        return view('admin.refunds.show', compact('refund'));
    }
}
