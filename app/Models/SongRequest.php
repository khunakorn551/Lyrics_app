<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'artist',
        'message',
        'status',
        'admin_response'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
