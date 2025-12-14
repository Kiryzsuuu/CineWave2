<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Film;
use Illuminate\Support\Facades\DB;

echo "Dropping all indexes on films collection...\n";

// Get MongoDB connection
$collection = DB::connection('mongodb')->getCollection('films');

// Get current indexes
$indexes = iterator_to_array($collection->listIndexes());
echo "Current indexes:\n";
foreach ($indexes as $index) {
    echo "- " . $index->getName() . "\n";
}

// Drop all indexes except _id
foreach ($indexes as $index) {
    if ($index->getName() !== '_id_') {
        echo "Dropping index: " . $index->getName() . "\n";
        $collection->dropIndex($index->getName());
    }
}

echo "\nCreating new indexes...\n";

// Create useful indexes
$collection->createIndex(['is_featured' => 1], ['name' => 'is_featured_1']);
$collection->createIndex(['is_trending' => 1], ['name' => 'is_trending_1']);
$collection->createIndex(['is_new' => 1], ['name' => 'is_new_1']);
$collection->createIndex(['slug' => 1], ['name' => 'slug_1', 'unique' => true]);

echo "Done!\n";

echo "\nTesting query after reindex:\n";
$trending = Film::where('is_trending', true)->count();
echo "Trending films: $trending\n";

$new = Film::where('is_new', true)->count();
echo "New films: $new\n";
