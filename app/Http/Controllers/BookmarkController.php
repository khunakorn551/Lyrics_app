<?php

namespace App\Http\Controllers;

use App\Models\Lyrics;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $bookmarks = auth()->user()->bookmarks()->with('lyrics')->paginate(10);
        return view('user.bookmarks.index', compact('bookmarks'));
    }

    public function store(Lyrics $lyric)
    {
        // Check if already bookmarked
        if (auth()->user()->bookmarks()->where('lyrics_id', $lyric->id)->exists()) {
            return back()->with('error', 'Lyrics already bookmarked.');
        }

        auth()->user()->bookmarks()->create([
            'lyrics_id' => $lyric->id
        ]);

        return back()->with('success', 'Lyrics bookmarked successfully.');
    }

    public function destroy(Bookmark $bookmark)
    {
        // Ensure the bookmark belongs to the authenticated user
        if ($bookmark->user_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $bookmark->delete();
        return back()->with('success', 'Bookmark removed successfully.');
    }
}
