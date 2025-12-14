<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Raw MongoDB query for films:\n\n";

$collection = DB::connection('mongodb')->getCollection('films');

$allFilms = $collection->find([], [
    'projection' => ['title' => 1, 'is_trending' => 1, 'is_new' => 1, 'is_featured' => 1]
]);

foreach ($allFilms as $film) {
    echo "Title: " . $film['title'] . "\n";
    echo "  is_featured: " . json_encode($film['is_featured'] ?? 'NOT SET') . "\n";
    echo "  is_trending: " . json_encode($film['is_trending'] ?? 'NOT SET') . "\n";
    echo "  is_new: " . json_encode($film['is_new'] ?? 'NOT SET') . "\n";
    echo "\n";
}

echo "\n--- Testing MongoDB queries directly ---\n\n";

$trendingQuery = $collection->find(['is_trending' => true]);
echo "Films with is_trending = true: " . iterator_count($collection->find(['is_trending' => true])) . "\n";

$newQuery = $collection->find(['is_new' => true]);
echo "Films with is_new = true: " . iterator_count($collection->find(['is_new' => true])) . "\n";
