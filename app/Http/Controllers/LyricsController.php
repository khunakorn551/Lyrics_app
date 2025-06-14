<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lyrics;
use Illuminate\Support\Facades\Storage;

class LyricsController extends Controller
{
    public function __construct()
    {
        // Only require auth for non-guest actions
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Lyrics::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('artist', 'like', "%{$searchTerm}%");
            });
        }

        $lyrics = $query->latest()->paginate(12);

        if (auth()->check() && auth()->user()->isAdmin()) {
            return view('admin.lyrics.index', compact('lyrics'));
        }

        return view('user.lyrics.index', compact('lyrics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'You must be logged in to perform this action.');
        }
        return view('admin.lyrics.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'You must be logged in to perform this action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'image' => 'required|image|max:5120' // Max 5MB
        ]);

        $validated['user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('lyrics-images', 'public');
        }

        Lyrics::create($validated);

        return redirect()->route('lyrics.index')
            ->with('success', 'Lyrics uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lyrics $lyric)
    {
        $lyric->load(['comments.user', 'comments.replies.user', 'comments.likes']);

        if (auth()->check() && auth()->user()->isAdmin()) {
            return view('admin.lyrics.show', compact('lyric'));
        }
        return view('user.lyrics.show', compact('lyric'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lyrics $lyric)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'You must be logged in to perform this action.');
        }
        return view('admin.lyrics.edit', compact('lyric'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lyrics $lyric)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'You must be logged in to perform this action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'image' => 'nullable|image|max:5120' // Max 5MB
        ]);

        if ($request->hasFile('image')) {
            if ($lyric->image_path) {
                Storage::disk('public')->delete($lyric->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('lyrics-images', 'public');
        }

        $lyric->update($validated);

        return redirect()->route('lyrics.index')
            ->with('success', 'Lyrics updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lyrics $lyric)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'You must be logged in to perform this action.');
        }

        if ($lyric->image_path) {
            Storage::disk('public')->delete($lyric->image_path);
        }
        
        $lyric->delete();

        return redirect()->route('lyrics.index')
            ->with('success', 'Lyrics deleted successfully.');
    }

    public function bookmark(Lyrics $lyric)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to bookmark lyrics.');
        }

        $user = auth()->user();
        $bookmark = $user->bookmarks()->where('lyrics_id', $lyric->id)->first();
        if ($bookmark) {
            $bookmark->delete();
            return back()->with('success', 'Bookmark removed.');
        } else {
            $user->bookmarks()->create(['lyrics_id' => $lyric->id]);
            return back()->with('success', 'Bookmarked!');
        }
    }

    public function report(Request $request, Lyrics $lyric)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to report lyrics.');
        }

        $request->validate([
            'reason' => 'required|string|max:255',
        ]);
        $user = auth()->user();
        $user->reports()->create([
            'lyrics_id' => $lyric->id,
            'reason' => $request->reason,
        ]);
        return back()->with('success', 'Your report has been submitted.');
    }
}
