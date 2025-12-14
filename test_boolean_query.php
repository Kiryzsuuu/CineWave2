<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Film;

echo "Testing different boolean query methods:\n\n";

// Method 1: where('field', true)
$method1 = Film::where('is_trending', true)->get(['title', 'is_trending']);
echo "Method 1 - where('is_trending', true): " . $method1->count() . " results\n";

// Method 2: where('field', '=', true)  
$method2 = Film::where('is_trending', '=', true)->get(['title', 'is_trending']);
echo "Method 2 - where('is_trending', '=', true): " . $method2->count() . " results\n";

// Method 3: where('field', 1)
$method3 = Film::where('is_trending', 1)->get(['title', 'is_trending']);
echo "Method 3 - where('is_trending', 1): " . $method3->count() . " results\n";

// Method 4: Raw query
$method4 = Film::raw(function($collection) {
    return $collection->find(['is_trending' => true]);
});
echo "Method 4 - raw query: " . count(iterator_to_array($method4)) . " results\n";

echo "\nAll films raw data:\n";
foreach (Film::all() as $film) {
    echo "- {$film->title}: ";
    echo "is_trending=" . var_export($film->is_trending, true) . " (" . gettype($film->is_trending) . "), ";
    echo "is_new=" . var_export($film->is_new ?? null, true) . " (" . gettype($film->is_new ?? null) . ")\n";
}
