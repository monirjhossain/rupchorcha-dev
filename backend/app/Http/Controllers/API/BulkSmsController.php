<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BulkSms;

class BulkSmsController extends Controller
{
    public function index()
    {
        $smsList = BulkSms::latest()->paginate(20);
        return response()->json(['success' => true, 'data' => $smsList]);
    }

    public function show($id)
    {
        $sms = BulkSms::findOrFail($id);
        return response()->json(['success' => true, 'data' => $sms]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'recipients' => 'required|array',
            'recipients.*' => 'required|string',
        ]);
        $bulkSms = BulkSms::create([
            'message' => $request->message,
            'recipients' => json_encode($request->recipients),
            'status' => 'pending',
        ]);

        // Example SMS sending logic (replace with actual provider integration)
        $success = true;
        foreach ($request->recipients as $recipient) {
            // Simulate sending SMS (replace with actual SMS gateway API call)
            // $result = SmsProvider::send($recipient, $request->message);
            // if (!$result) $success = false;
        }
        $bulkSms->status = $success ? 'sent' : 'failed';
        $bulkSms->save();
        return response()->json(['success' => true, 'data' => $bulkSms, 'message' => 'SMS processed.'], 201);
    }

    public function update(Request $request, $id)
    {
        $bulkSms = BulkSms::findOrFail($id);
        $bulkSms->update($request->all());
        return response()->json(['success' => true, 'data' => $bulkSms]);
    }

    public function destroy($id)
    {
        BulkSms::destroy($id);
        return response()->json(['success' => true, 'message' => 'Record deleted'], 200);
    }
}
