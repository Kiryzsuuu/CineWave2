<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Comment extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'comments';

    protected $fillable = [
        'film_id',
        'user_id',
        'user_name',
        'body',
        'rating',
        'parent_id',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function film()
    {
        return $this->belongsTo(Film::class, 'film_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
