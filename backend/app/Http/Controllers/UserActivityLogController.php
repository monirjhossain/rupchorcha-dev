<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class UserActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = UserActivityLog::with('user')->latest()->paginate(30);
        return view('admin.users.activity_logs', compact('logs'));
    }
    public function show($userId)
    {
        $user = User::with('activityLogs')->findOrFail($userId);
        return view('admin.users.activity_logs_user', compact('user'));
    }
}
