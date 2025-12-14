<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Film;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FilmController extends Controller
{
    public function index()
    {
        $films = Film::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.films.index', compact('films'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.films.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'duration' => 'required|integer|min:1',
            'rating' => 'nullable|numeric|min:0|max:10',
            'genre' => 'required|array',
            'categories' => 'nullable|array',
            'poster_url' => 'nullable|url',
            'backdrop_url' => 'nullable|url',
            'trailer_url' => 'nullable|url',
            'video_url' => 'required|url',
            'director' => 'nullable|string',
            'cast' => 'nullable|array',
            'language' => 'nullable|string',
            'country' => 'nullable|string',
        ]);

        $film = Film::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'year' => $request->year,
            'duration' => $request->duration,
            'rating' => $request->rating ?? 0,
            'genre' => $request->genre,
            'categories' => $request->categories ?? [],
            'poster_url' => $request->poster_url,
            'backdrop_url' => $request->backdrop_url,
            'trailer_url' => $request->trailer_url,
            'video_url' => $request->video_url,
            'director' => $request->director,
            'cast' => $request->cast ?? [],
            'language' => $request->language,
            'country' => $request->country,
            'is_featured' => $request->is_featured ?? false,
            'is_trending' => $request->is_trending ?? false,
            'is_new_release' => $request->is_new_release ?? false,
            'views_count' => 0,
            'likes_count' => 0,
        ]);

        ActivityLog::logActivity(auth()->id(), 'film_created', "Created film: {$film->title}", ['film_id' => $film->id]);

        return redirect()->route('admin.films.index')->with('success', 'Film berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $film = Film::findOrFail($id);
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.films.edit', compact('film', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $film = Film::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'duration' => 'required|integer|min:1',
            'rating' => 'nullable|numeric|min:0|max:10',
            'genre' => 'required|array',
            'categories' => 'nullable|array',
            'video_url' => 'required|url',
        ]);

        $film->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'year' => $request->year,
            'duration' => $request->duration,
            'rating' => $request->rating ?? 0,
            'genre' => $request->genre,
            'categories' => $request->categories ?? [],
            'poster_url' => $request->poster_url,
            'backdrop_url' => $request->backdrop_url,
            'trailer_url' => $request->trailer_url,
            'video_url' => $request->video_url,
            'director' => $request->director,
            'cast' => $request->cast ?? [],
            'language' => $request->language,
            'country' => $request->country,
            'is_featured' => $request->is_featured ?? false,
            'is_trending' => $request->is_trending ?? false,
            'is_new_release' => $request->is_new_release ?? false,
        ]);

        ActivityLog::logActivity(auth()->id(), 'film_updated', "Updated film: {$film->title}", ['film_id' => $film->id]);

        return redirect()->route('admin.films.index')->with('success', 'Film berhasil diupdate!');
    }

    public function destroy(string $id)
    {
        $film = Film::findOrFail($id);
        
        ActivityLog::logActivity(auth()->id(), 'film_deleted', "Deleted film: {$film->title}", ['film_id' => $film->id]);

        $film->delete();

        return redirect()->route('admin.films.index')->with('success', 'Film berhasil dihapus!');
    }
}
