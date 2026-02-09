<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BulkEmail;
use Illuminate\Support\Facades\Mail;

class BulkEmailController extends Controller
{
    public function index()
    {
        $emails = BulkEmail::latest()->paginate(20);
        return response()->json(['success' => true, 'data' => $emails]);
    }

    public function show($id)
    {
        $email = BulkEmail::findOrFail($id);
        return response()->json(['success' => true, 'data' => $email]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string',
            'body' => 'required|string',
            'recipients' => 'required|array',
            'recipients.*' => 'required|email',
        ]);
        $bulkEmail = BulkEmail::create([
            'subject' => $request->subject,
            'body' => $request->body,
            'recipients' => json_encode($request->recipients),
            'status' => 'pending',
        ]);
        // Example email sending logic
        $success = true;
        foreach ($request->recipients as $recipient) {
            // Replace with actual Mail::send logic
            // Mail::to($recipient)->send(new BulkEmailMailable($request->subject, $request->body));
        }
        $bulkEmail->status = $success ? 'sent' : 'failed';
        $bulkEmail->save();
        return response()->json(['success' => true, 'data' => $bulkEmail, 'message' => 'Bulk emails sent.'], 201);
    }

    public function update(Request $request, $id)
    {
        $bulkEmail = BulkEmail::findOrFail($id);
        $bulkEmail->update($request->all());
        return response()->json(['success' => true, 'data' => $bulkEmail]);
    }

    public function destroy($id)
    {
        BulkEmail::destroy($id);
        return response()->json(['success' => true, 'message' => 'Record deleted'], 200);
    }
}
