<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Watchlist extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'watchlists';

    protected $fillable = [
        'user_id',
        'film_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship to Film
    public function film()
    {
        return $this->belongsTo(Film::class, 'film_id');
    }
}
