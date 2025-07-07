<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LyricsController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\SongRequestController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

// Redirect /admin to /admin/dashboard
Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
})->name('admin.redirect');

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
    // User dashboard
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user && $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $bookmarkedLyrics = $user->bookmarks()->with('lyrics')->latest()->take(4)->get();
        $recentSongRequests = $user->songRequests()->latest()->take(5)->get();

        return view('dashboard', compact('bookmarkedLyrics', 'recentSongRequests'));
    })->name('dashboard');

    // Admin routes using direct class reference
    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/lyrics', [LyricsController::class, 'index'])->name('admin.lyrics.index');
        Route::get('/admin/lyrics/create', [LyricsController::class, 'create'])->name('lyrics.create');
        Route::post('/admin/lyrics', [LyricsController::class, 'store'])->name('lyrics.store');
        Route::get('/admin/lyrics/{lyric}/edit', [LyricsController::class, 'edit'])->name('lyrics.edit');
        Route::put('/admin/lyrics/{lyric}', [LyricsController::class, 'update'])->name('lyrics.update');
        Route::delete('/admin/lyrics/{lyric}', [LyricsController::class, 'destroy'])->name('lyrics.destroy');
    });

    // Authenticated user actions
    Route::post('/lyrics/{lyric}/bookmark', [LyricsController::class, 'bookmark'])->name('lyrics.bookmark');
    Route::post('/lyrics/{lyric}/report', [LyricsController::class, 'report'])->name('lyrics.report');

    // Bookmarks
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/bookmarks/{lyric}', [BookmarkController::class, 'store'])->name('bookmarks.store');
    Route::delete('/bookmarks/{bookmark}', [BookmarkController::class, 'destroy'])->name('bookmarks.destroy');

    // Song requests
    Route::get('/song-requests', [SongRequestController::class, 'index'])->name('song-requests.index');
    Route::get('/song-requests/create', [SongRequestController::class, 'create'])->name('song-requests.create');
    Route::post('/song-requests', [SongRequestController::class, 'store'])->name('song-requests.store');
    Route::get('/song-requests/{songRequest}', [SongRequestController::class, 'show'])->name('song-requests.show');
    Route::delete('/song-requests/{songRequest}', [SongRequestController::class, 'destroy'])->name('song-requests.destroy');
    Route::put('/song-requests/{songRequest}', [SongRequestController::class, 'update'])->name('song-requests.update');

    // Comments
    Route::post('/lyrics/{lyric}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');
    Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like');
    Route::delete('/comments/{comment}/unlike', [CommentController::class, 'unlike'])->name('comments.unlike');


    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/sitemap.xml', function () {
    $sitemap = Sitemap::create()
        ->add(Url::create('/'))
        ->add(Url::create('/lyrics'))
        ->add(Url::create('/about'))
        ->add(Url::create('/contact'));
    return $sitemap->toResponse(request());
});

require __DIR__.'/auth.php';
