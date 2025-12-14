<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredFilms = Film::where('is_featured', true)->orderBy('created_at', 'desc')->limit(10)->get();
        $trendingFilms = Film::where('is_trending', true)->orderBy('views_count', 'desc')->limit(10)->get();
        $newReleases = Film::where('is_new_release', true)->orderBy('created_at', 'desc')->limit(10)->get();
        $categories = Category::where('is_active', true)->orderBy('order')->get();
        
        $categoryFilms = [];
        foreach ($categories->take(5) as $category) {
            $categoryFilms[$category->name] = Film::whereJsonContains('categories', $category->slug)
                                                  ->orderBy('rating', 'desc')
                                                  ->limit(10)
                                                  ->get();
        }

        return view('home', compact('featuredFilms', 'trendingFilms', 'newReleases', 'categories', 'categoryFilms'));
    }

    public function browse(Request $request)
    {
        $query = Film::query();

        if ($request->has('genre') && $request->genre) {
            $query->whereJsonContains('genre', $request->genre);
        }

        if ($request->has('category') && $request->category) {
            $query->whereJsonContains('categories', $request->category);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $films = $query->orderBy('created_at', 'desc')->paginate(20);
        $categories = Category::where('is_active', true)->orderBy('order')->get();

        return view('browse', compact('films', 'categories'));
    }

    public function show(string $slug)
    {
        $film = Film::where('slug', $slug)->firstOrFail();
        
        // Increment views
        $film->incrementViews();
        
        // Log activity
        if (auth()->check()) {
            ActivityLog::logActivity(auth()->id(), 'film_view', "Viewed film: {$film->title}", ['film_id' => $film->id]);
        }

        // Get similar films
        $similarFilms = Film::where('_id', '!=', $film->id)
                           ->where(function($query) use ($film) {
                               foreach ($film->genre as $genre) {
                                   $query->orWhereJsonContains('genre', $genre);
                               }
                           })
                           ->limit(10)
                           ->get();

        return view('film.show', compact('film', 'similarFilms'));
    }

    public function watch(string $slug)
    {
        $film = Film::where('slug', $slug)->firstOrFail();

        // Check if user has subscription
        if (auth()->check() && !auth()->user()->hasActiveSubscription()) {
            return redirect()->route('subscription')->with('error', 'Anda perlu berlangganan untuk menonton film ini.');
        }

        // Log activity
        if (auth()->check()) {
            ActivityLog::logActivity(auth()->id(), 'film_watch', "Watched film: {$film->title}", ['film_id' => $film->id]);
            
            // Add to watch history
            $user = auth()->user();
            $watchHistory = $user->watch_history ?? [];
            
            // Add film ID to beginning of array (recent first)
            array_unshift($watchHistory, [
                'film_id' => $film->id,
                'watched_at' => now(),
            ]);
            
            // Keep only last 50 items
            $watchHistory = array_slice($watchHistory, 0, 50);
            
            $user->update(['watch_history' => $watchHistory]);
        }

        return view('film.watch', compact('film'));
    }
}
