<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LyricsController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\SongRequestController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/lyrics', [LyricsController::class, 'index'])->name('lyrics.index');
Route::get('/lyrics/{lyric}', [LyricsController::class, 'show'])->name('lyrics.show');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Dashboard routes
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return app()->make(\App\Http\Controllers\Admin\DashboardController::class)->index();
        }

        // For regular users
        $user = auth()->user();
        $bookmarkedLyrics = $user->bookmarks()->with('lyrics')->latest()->take(4)->get();
        $recentSongRequests = $user->songRequests()->latest()->take(5)->get();

        return view('user.dashboard', compact('bookmarkedLyrics', 'recentSongRequests'));
    })->name('dashboard');

    // Admin-only routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/lyrics/create', [LyricsController::class, 'create'])->name('lyrics.create');
        Route::post('/lyrics', [LyricsController::class, 'store'])->name('lyrics.store');
        Route::get('/lyrics/{lyric}/edit', [LyricsController::class, 'edit'])->name('lyrics.edit');
        Route::put('/lyrics/{lyric}', [LyricsController::class, 'update'])->name('lyrics.update');
        Route::delete('/lyrics/{lyric}', [LyricsController::class, 'destroy'])->name('lyrics.destroy');
    });

    // Authenticated user routes
    Route::post('/lyrics/{lyric}/bookmark', [LyricsController::class, 'bookmark'])->name('lyrics.bookmark');
    Route::post('/lyrics/{lyric}/report', [LyricsController::class, 'report'])->name('lyrics.report');
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/bookmarks/{lyric}', [BookmarkController::class, 'store'])->name('bookmarks.store');
});

require __DIR__.'/auth.php';
