<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lyrics extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'artist',
        'content',
        'user_id',
        'image_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function isBookmarkedBy(User $user)
    {
        return $this->bookmarks()->where('user_id', $user->id)->exists();
    }

    public function reports()
    {
        return $this->hasMany(\App\Models\Report::class, 'lyrics_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
