<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'films';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'year',
        'duration',
        'rating',
        'genre',
        'categories',
        'poster_url',
        'backdrop_url',
        'trailer_url',
        'video_url',
        'director',
        'cast',
        'language',
        'country',
        'is_featured',
        'is_trending',
        'is_new_release',
        'views_count',
        'likes_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'year' => 'integer',
        'duration' => 'integer',
        'rating' => 'float',
        'categories' => 'array',
        'cast' => 'array',
        'genre' => 'array',
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
        'is_new_release' => 'boolean',
        'views_count' => 'integer',
        'likes_count' => 'integer',
        'meta_keywords' => 'array',
    ];

    /**
     * Get the categories for the film.
     */
    public function categoryModels()
    {
        return $this->belongsToMany(Category::class, null, 'film_ids', 'category_ids');
    }

    /**
     * Increment views count.
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Increment likes count.
     */
    public function incrementLikes()
    {
        $this->increment('likes_count');
    }
}
