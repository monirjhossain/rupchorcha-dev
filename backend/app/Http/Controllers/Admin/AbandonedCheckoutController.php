<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbandonedCheckout;
use Illuminate\Http\Request;

class AbandonedCheckoutController extends Controller
{
    public function index(Request $request)
    {
        $query = AbandonedCheckout::with('user');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        $abandoned = $query->orderByDesc('last_activity_at')->paginate(30);
        return view('admin.abandoned_checkouts.index', compact('abandoned'));
    }
}
