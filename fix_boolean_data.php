<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Fixing boolean fields in films collection...\n\n";

$collection = DB::connection('mongodb')->getCollection('films');

// Fix all films
$allFilms = $collection->find();
$updated = 0;

foreach ($allFilms as $film) {
    $updateData = [];
    
    // Fix is_featured
    if (isset($film['is_featured'])) {
        if ($film['is_featured'] === "1" || $film['is_featured'] === 1) {
            $updateData['is_featured'] = true;
        } elseif ($film['is_featured'] === "0" || $film['is_featured'] === 0 || $film['is_featured'] === "") {
            $updateData['is_featured'] = false;
        }
    } else {
        $updateData['is_featured'] = false;
    }
    
    // Fix is_trending
    if (isset($film['is_trending'])) {
        if ($film['is_trending'] === "1" || $film['is_trending'] === 1) {
            $updateData['is_trending'] = true;
        } elseif ($film['is_trending'] === "0" || $film['is_trending'] === 0 || $film['is_trending'] === "") {
            $updateData['is_trending'] = false;
        }
    } else {
        $updateData['is_trending'] = false;
    }
    
    // Fix is_new
    if (isset($film['is_new'])) {
        if ($film['is_new'] === "1" || $film['is_new'] === 1) {
            $updateData['is_new'] = true;
        } elseif ($film['is_new'] === "0" || $film['is_new'] === 0 || $film['is_new'] === "") {
            $updateData['is_new'] = false;
        }
    } else {
        $updateData['is_new'] = false;
    }
    
    if (!empty($updateData)) {
        $collection->updateOne(
            ['_id' => $film['_id']],
            ['$set' => $updateData]
        );
        echo "Updated: " . $film['title'] . "\n";
        foreach ($updateData as $key => $value) {
            echo "  $key: " . json_encode($value) . "\n";
        }
        $updated++;
    }
}

echo "\nTotal films updated: $updated\n";

echo "\n--- Verification ---\n";
$trending = iterator_count($collection->find(['is_trending' => true]));
echo "Films with is_trending = true: $trending\n";

$new = iterator_count($collection->find(['is_new' => true]));
echo "Films with is_new = true: $new\n";

$featured = iterator_count($collection->find(['is_featured' => true]));
echo "Films with is_featured = true: $featured\n";
