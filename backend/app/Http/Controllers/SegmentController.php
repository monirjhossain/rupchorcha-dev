<?php

namespace App\Http\Controllers;

use App\Models\Segment;
use Illuminate\Http\Request;

class SegmentController extends Controller
{
    // List all segments
    public function index()
    {
        $segments = Segment::orderBy('created_at', 'desc')->get();
        return view('admin.segments.index', compact('segments'));
    }

    // Store new segment (AJAX)
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'rules' => 'required|array',
        ]);
        $segment = Segment::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'rules' => $data['rules'],
        ]);
        return response()->json(['success' => true, 'segment' => $segment]);
    }

    // Update segment (AJAX)
    public function update(Request $request, Segment $segment)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'rules' => 'required|array',
        ]);
        $segment->update($data);
        return response()->json(['success' => true, 'segment' => $segment]);
    }

    // Delete segment (AJAX)
    public function destroy(Segment $segment)
    {
        $segment->delete();
        return response()->json(['success' => true]);
    }

    // Preview users in segment (AJAX)
    public function preview(Segment $segment, Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $users = $segment->usersQuery()->paginate($perPage);
        return response()->json([
            'users' => $users->items(),
            'total' => $users->total(),
            'current_page' => $users->currentPage(),
            'last_page' => $users->lastPage(),
        ]);
    }
}
