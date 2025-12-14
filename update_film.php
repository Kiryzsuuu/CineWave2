<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Film;

$film = Film::where('title', 'like', '%Pertalite%')->first();

if ($film) {
    $film->is_new = true;
    $film->save();
    
    echo "Film updated successfully!\n";
    echo "Title: " . $film->title . "\n";
    echo "is_trending: " . ($film->is_trending ? 'true' : 'false') . "\n";
    echo "is_new: " . ($film->is_new ? 'true' : 'false') . "\n";
    echo "is_featured: " . ($film->is_featured ? 'true' : 'false') . "\n";
} else {
    echo "Film not found!\n";
}
