<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Film;
use App\Models\Watchlist;

// Get admin user
$user = User::where('email', 'maskiryz23@gmail.com')->first();
if (!$user) {
    echo "User not found!\n";
    exit;
}

// Get a film
$film = Film::first();
if (!$film) {
    echo "No films found!\n";
    exit;
}

echo "User ID: " . $user->id . " (type: " . gettype($user->id) . ")\n";
echo "Film ID: " . $film->id . " (type: " . gettype($film->id) . ")\n";

// Check if watchlist exists
$exists = Watchlist::where('user_id', (string)$user->id)
    ->where('film_id', (string)$film->id)
    ->first();

if ($exists) {
    echo "Watchlist already exists!\n";
    echo "Watchlist ID: " . $exists->id . "\n";
} else {
    echo "Creating new watchlist entry...\n";
    
    $watchlist = Watchlist::create([
        'user_id' => (string)$user->id,
        'film_id' => (string)$film->id,
    ]);
    
    echo "Watchlist created!\n";
    echo "Watchlist ID: " . $watchlist->id . "\n";
}

// List all watchlists for this user
echo "\nAll watchlists for user:\n";
$watchlists = Watchlist::where('user_id', (string)$user->id)->get();
echo "Total: " . $watchlists->count() . "\n";

foreach ($watchlists as $w) {
    echo "- Watchlist ID: {$w->id}, Film ID: {$w->film_id}\n";
}
