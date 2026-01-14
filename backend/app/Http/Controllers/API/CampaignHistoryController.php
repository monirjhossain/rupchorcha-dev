<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CampaignHistory;

class CampaignHistoryController extends Controller
{
    public function index()
    {
        return CampaignHistory::all();
    }

    public function show($id)
    {
        return CampaignHistory::findOrFail($id);
    }

    public function store(Request $request)
    {
        $campaignHistory = CampaignHistory::create($request->all());
        return response()->json($campaignHistory, 201);
    }

    public function update(Request $request, $id)
    {
        $campaignHistory = CampaignHistory::findOrFail($id);
        $campaignHistory->update($request->all());
        return response()->json($campaignHistory);
    }

    public function destroy($id)
    {
        CampaignHistory::destroy($id);
        return response()->json(null, 204);
    }
}
