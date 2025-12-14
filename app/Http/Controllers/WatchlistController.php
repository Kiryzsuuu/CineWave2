<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use App\Models\Film;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get all watchlist entries for the user - convert to string for MongoDB
        $watchlistIds = Watchlist::where('user_id', (string)$user->id)->pluck('film_id')->toArray();
        
        // Get the actual films - MongoDB needs array of strings
        $films = Film::whereIn('_id', $watchlistIds)->get();
        
        return view('watchlist.index', compact('films'));
    }

    public function toggle(Request $request, $filmId)
    {
        $user = auth()->user();
        
        // Check if already in watchlist - convert IDs to strings for MongoDB
        $watchlist = Watchlist::where('user_id', (string)$user->id)
            ->where('film_id', (string)$filmId)
            ->first();
        
        if ($watchlist) {
            // Remove from watchlist
            $watchlist->delete();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'action' => 'removed',
                    'message' => 'Film removed from watchlist'
                ]);
            }
            
            return back()->with('success', 'Film dihapus dari watchlist');
        } else {
            // Add to watchlist - convert IDs to strings for MongoDB
            Watchlist::create([
                'user_id' => (string)$user->id,
                'film_id' => (string)$filmId,
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'action' => 'added',
                    'message' => 'Film added to watchlist'
                ]);
            }
            
            return back()->with('success', 'Film ditambahkan ke watchlist');
        }
    }

    public function check($filmId)
    {
        $user = auth()->user();
        
        $exists = Watchlist::where('user_id', (string)$user->id)
            ->where('film_id', (string)$filmId)
            ->exists();
        
        return response()->json([
            'in_watchlist' => $exists
        ]);
    }
}
