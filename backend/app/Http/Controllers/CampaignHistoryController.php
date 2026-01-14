<?php

namespace App\Http\Controllers;

use App\Models\MarketingCampaign;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CampaignHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = MarketingCampaign::with('admin');
        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }
        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }
        $campaigns = $query->orderBy('created_at', 'desc')->paginate(20);
        $admins = User::whereIn('role', ['admin','super_admin'])->get();

        // Stats: campaigns per month (last 12 months)
        $monthlyStats = MarketingCampaign::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total, SUM(recipient_count) as recipients')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        // Stats: campaigns per admin (all time)
        $userStats = MarketingCampaign::selectRaw('admin_id, COUNT(*) as total, SUM(recipient_count) as recipients')
            ->groupBy('admin_id')
            ->with('admin')
            ->get();

        return view('admin.marketing.campaign_history', compact('campaigns', 'admins', 'monthlyStats', 'userStats'));
    }
}
