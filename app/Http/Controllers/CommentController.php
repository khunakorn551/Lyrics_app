<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Lyrics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Lyrics $lyric)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = $lyric->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return back()->with('success', 'Comment posted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified comment in storage (for admin replies).
     */
    public function update(Request $request, Comment $comment)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update([
            'content' => $validated['content'], // Admin can edit content directly
        ]);

        return back()->with('success', 'Comment updated successfully.');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment)
    {
        // Only the owner or an admin can delete a comment
        if (!Auth::user()->isAdmin() && Auth::id() !== $comment->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully.');
    }

    /**
     * Like a comment.
     */
    public function like(Comment $comment)
    {
        // A user can only like a comment once
        if (!$comment->likes()->where('user_id', Auth::id())->exists()) {
            $comment->likes()->create(['user_id' => Auth::id()]);
            $comment->increment('likes_count');
        }

        return back();
    }

    /**
     * Unlike a comment.
     */
    public function unlike(Comment $comment)
    {
        $comment->likes()->where('user_id', Auth::id())->delete();
        $comment->decrement('likes_count');

        return back();
    }
}
