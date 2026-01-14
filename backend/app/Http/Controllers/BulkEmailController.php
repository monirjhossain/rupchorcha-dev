<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MarketingCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BulkEmailController extends Controller
{
    // Show bulk email form
    public function index(Request $request)
    {
        $query = User::query();
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }
        $users = $query->get();
        return view('admin.marketing.bulk_email', compact('users'));
    }

    // Handle sending bulk email
    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string',
            'body' => 'required|string',
            'user_ids' => 'required|array',
        ]);
        $users = User::whereIn('id', $request->user_ids)->get();
        $count = 0;
        foreach ($users as $user) {
            Mail::raw($request->body, function($mail) use ($user, $request) {
                $mail->to($user->email)
                    ->subject($request->subject);
            });
            $count++;
        }
        // Log campaign
        MarketingCampaign::create([
            'admin_id' => auth()->id(),
            'type' => 'email',
            'subject' => $request->subject,
            'body' => $request->body,
            'recipient_count' => $count,
            'recipients' => $users->pluck('email')->toArray(),
        ]);
        return redirect()->route('bulk_email.index')->with('success', "Bulk email sent to $count users.");
    }
}
