<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CampaignHistory;

class CampaignHistoryController extends Controller
{
    public function index()
    {
        $history = CampaignHistory::latest()->paginate(20);
        return response()->json(['success' => true, 'data' => $history]);
    }

    public function show($id)
    {
        $history = CampaignHistory::findOrFail($id);
        return response()->json(['success' => true, 'data' => $history]);
    }

    public function store(Request $request)
    {
        $campaignHistory = CampaignHistory::create($request->all());
        return response()->json(['success' => true, 'data' => $campaignHistory], 201);
    }

    public function update(Request $request, $id)
    {
        $campaignHistory = CampaignHistory::findOrFail($id);
        $campaignHistory->update($request->all());
        return response()->json(['success' => true, 'data' => $campaignHistory]);
    }

    public function destroy($id)
    {
        CampaignHistory::destroy($id);
        return response()->json(null, 204);
    }
}
