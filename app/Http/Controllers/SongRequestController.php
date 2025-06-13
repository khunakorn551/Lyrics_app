<?php

namespace App\Http\Controllers;

use App\Models\SongRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SongRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            $requests = SongRequest::with('user')->latest()->paginate(10);
            return view('admin.song-requests.index', compact('requests'));
        }

        $requests = Auth::user()->songRequests()->latest()->paginate(10);
        return view('user.song-requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.song-requests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'message' => 'nullable|string'
        ]);

        $validated['user_id'] = Auth::id();

        SongRequest::create($validated);

        return redirect()->route('song-requests.index')
            ->with('success', 'Song request submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SongRequest $songRequest)
    {
        if (!Auth::user()->isAdmin() && Auth::id() !== $songRequest->user_id) {
            abort(403);
        }

        return view('song-requests.show', compact('songRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SongRequest $songRequest)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_response' => 'nullable|string'
        ]);

        $songRequest->update($validated);

        return redirect()->route('song-requests.index')
            ->with('success', 'Song request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SongRequest $songRequest)
    {
        if (!Auth::user()->isAdmin() && Auth::id() !== $songRequest->user_id) {
            abort(403);
        }

        $songRequest->delete();

        return redirect()->route('song-requests.index')
            ->with('success', 'Song request deleted successfully.');
    }
}
