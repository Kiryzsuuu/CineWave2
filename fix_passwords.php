<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Fixing user passwords...\n\n";

$users = User::all();
$fixed = 0;

foreach ($users as $user) {
    // Check if password is already bcrypt hashed
    // Bcrypt hashes start with $2y$ and are 60 characters long
    if (!str_starts_with($user->password, '$2y$') || strlen($user->password) !== 60) {
        echo "Fixing password for user: {$user->email}\n";
        
        // If it looks like a plain password, hash it
        $user->password = Hash::make($user->password);
        $user->save();
        
        echo "  New hash: " . substr($user->password, 0, 30) . "...\n";
        $fixed++;
    } else {
        echo "User {$user->email} - password already hashed correctly\n";
    }
}

echo "\nTotal users fixed: $fixed\n";
echo "Total users checked: " . $users->count() . "\n";

if ($fixed > 0) {
    echo "\nIMPORTANT: Some passwords were re-hashed from their stored values.\n";
    echo "If you need to reset passwords manually, use these commands:\n\n";
    
    foreach ($users as $user) {
        echo "User: {$user->email}\n";
        if ($user->email === 'maskiryz23@gmail.com') {
            echo "  Suggested: Set to 'admin123'\n";
        }
    }
}
