<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LyricsController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\SongRequestController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Redirect /admin to /admin/dashboard to prevent controller resolution errors
Route::redirect('/admin', '/admin/dashboard');

// Public routes
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/lyrics', [LyricsController::class, 'index'])->name('lyrics.index');
Route::get('/lyrics/{lyric}', [LyricsController::class, 'show'])->name('lyrics.show');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard routes
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user && $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $bookmarkedLyrics = $user->bookmarks()->with('lyrics')->latest()->take(4)->get();
        $recentSongRequests = $user->songRequests()->latest()->take(5)->get();

        return view('dashboard', compact('bookmarkedLyrics', 'recentSongRequests'));
    })->name('dashboard');

    // Admin routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
        
        Route::get('/admin/lyrics', [LyricsController::class, 'index'])->name('admin.lyrics.index');
        Route::get('/admin/lyrics/create', [LyricsController::class, 'create'])->name('lyrics.create');
        Route::post('/admin/lyrics', [LyricsController::class, 'store'])->name('lyrics.store');
        Route::get('/admin/lyrics/{lyric}/edit', [LyricsController::class, 'edit'])->name('lyrics.edit');
        Route::put('/admin/lyrics/{lyric}', [LyricsController::class, 'update'])->name('lyrics.update');
        Route::delete('/admin/lyrics/{lyric}', [LyricsController::class, 'destroy'])->name('lyrics.destroy');
    });

    // Authenticated user routes
    Route::post('/lyrics/{lyric}/bookmark', [LyricsController::class, 'bookmark'])->name('lyrics.bookmark');
    Route::post('/lyrics/{lyric}/report', [LyricsController::class, 'report'])->name('lyrics.report');

    // Bookmark routes
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/bookmarks/{lyric}', [BookmarkController::class, 'store'])->name('bookmarks.store');
    Route::delete('/bookmarks/{bookmark}', [BookmarkController::class, 'destroy'])->name('bookmarks.destroy');

    // Song Request routes
    Route::get('/song-requests', [SongRequestController::class, 'index'])->name('song-requests.index');
    Route::get('/song-requests/create', [SongRequestController::class, 'create'])->name('song-requests.create');
    Route::post('/song-requests', [SongRequestController::class, 'store'])->name('song-requests.store');
    Route::get('/song-requests/{songRequest}', [SongRequestController::class, 'show'])->name('song-requests.show');
    Route::delete('/song-requests/{songRequest}', [SongRequestController::class, 'destroy'])->name('song-requests.destroy');

    // Comment routes
    Route::post('/lyrics/{lyric}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');
    Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
