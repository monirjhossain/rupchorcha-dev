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
        return BulkEmail::all();
    }

    public function show($id)
    {
        return BulkEmail::findOrFail($id);
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
        return response()->json($bulkEmail, 201);
    }

    public function update(Request $request, $id)
    {
        $bulkEmail = BulkEmail::findOrFail($id);
        $bulkEmail->update($request->all());
        return response()->json($bulkEmail);
    }

    public function destroy($id)
    {
        BulkEmail::destroy($id);
        return response()->json(null, 204);
    }
}
