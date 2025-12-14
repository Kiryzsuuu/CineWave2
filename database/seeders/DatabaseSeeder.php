<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Film;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin CineWave',
            'email' => 'maskiryz23@gmail.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'is_verified' => true,
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Action', 'slug' => 'action', 'order' => 1, 'is_active' => true],
            ['name' => 'Comedy', 'slug' => 'comedy', 'order' => 2, 'is_active' => true],
            ['name' => 'Drama', 'slug' => 'drama', 'order' => 3, 'is_active' => true],
            ['name' => 'Horror', 'slug' => 'horror', 'order' => 4, 'is_active' => true],
            ['name' => 'Romance', 'slug' => 'romance', 'order' => 5, 'is_active' => true],
            ['name' => 'Sci-Fi', 'slug' => 'sci-fi', 'order' => 6, 'is_active' => true],
            ['name' => 'Thriller', 'slug' => 'thriller', 'order' => 7, 'is_active' => true],
            ['name' => 'Documentary', 'slug' => 'documentary', 'order' => 8, 'is_active' => true],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create Sample Films
        $sampleFilms = [
            [
                'title' => 'The Shawshank Redemption',
                'slug' => 'the-shawshank-redemption',
                'description' => 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.',
                'year' => 1994,
                'duration' => 142,
                'rating' => 9.3,
                'genre' => ['Drama'],
                'categories' => ['drama'],
                'poster_url' => 'https://image.tmdb.org/t/p/w500/q6y0Go1tsGEsmtFryDOJo3dEmqu.jpg',
                'backdrop_url' => 'https://image.tmdb.org/t/p/original/kXfqcdQKsToO0OUXHcrrNCHDBzO.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=6hB3S9bIaco',
                'video_url' => 'https://www.youtube.com/watch?v=6hB3S9bIaco',
                'director' => 'Frank Darabont',
                'cast' => ['Tim Robbins', 'Morgan Freeman'],
                'language' => 'English',
                'country' => 'USA',
                'is_featured' => true,
                'is_trending' => true,
                'is_new' => false,
                'views_count' => 0,
                'likes_count' => 0,
            ],
            [
                'title' => 'The Godfather',
                'slug' => 'the-godfather',
                'description' => 'The aging patriarch of an organized crime dynasty transfers control of his clandestine empire to his reluctant son.',
                'year' => 1972,
                'duration' => 175,
                'rating' => 9.2,
                'genre' => ['Crime', 'Drama'],
                'categories' => ['drama'],
                'poster_url' => 'https://image.tmdb.org/t/p/w500/3bhkrj58Vtu7enYsRolD1fZdja1.jpg',
                'backdrop_url' => 'https://image.tmdb.org/t/p/original/tmU7GeKVybMWFButWEGl2M4GeiP.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=sY1S34973zA',
                'video_url' => 'https://www.youtube.com/watch?v=sY1S34973zA',
                'director' => 'Francis Ford Coppola',
                'cast' => ['Marlon Brando', 'Al Pacino'],
                'language' => 'English',
                'country' => 'USA',
                'is_featured' => true,
                'is_trending' => false,
                'is_new' => false,
                'views_count' => 0,
                'likes_count' => 0,
            ],
        ];

        foreach ($sampleFilms as $film) {
            Film::create($film);
        }
    }
}
