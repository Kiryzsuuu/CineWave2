<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'order',
        'is_active',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the films for the category.
     */
    public function films()
    {
        return $this->belongsToMany(Film::class, null, 'category_ids', 'film_ids');
    }
}
