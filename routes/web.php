<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LyricsController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\SongRequestController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Moved /lyrics/create here
    Route::get('/lyrics/create', [LyricsController::class, 'create'])->name('lyrics.create');

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

    // Lyrics routes
    Route::get('/lyrics', [LyricsController::class, 'index'])->name('lyrics.index');
    Route::get('/lyrics/{lyric}', [LyricsController::class, 'show'])->name('lyrics.show');
    Route::post('/lyrics', [LyricsController::class, 'store'])->name('lyrics.store');
    Route::get('/lyrics/{lyric}/edit', [LyricsController::class, 'edit'])->name('lyrics.edit');
    Route::put('/lyrics/{lyric}', [LyricsController::class, 'update'])->name('lyrics.update');
    Route::delete('/lyrics/{lyric}', [LyricsController::class, 'destroy'])->name('lyrics.destroy');

    // Bookmark routes
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/bookmarks/{lyric}', [BookmarkController::class, 'store'])->name('bookmarks.store');
    Route::delete('/bookmarks/{lyric}', [BookmarkController::class, 'destroy'])->name('bookmarks.destroy');

    // Song Request routes
    Route::resource('song-requests', SongRequestController::class);

    // Comments routes
    Route::post('/lyrics/{lyric}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like');
    Route::post('/comments/{comment}/unlike', [CommentController::class, 'unlike'])->name('comments.unlike');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');

require __DIR__.'/auth.php';
