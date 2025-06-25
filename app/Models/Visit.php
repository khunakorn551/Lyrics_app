<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'visited_at',
        'last_activity',
        'duration',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 