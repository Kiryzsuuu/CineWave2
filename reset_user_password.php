<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Resetting password for kiryzsu@gmail.com to 'admin123'...\n";

$user = User::where('email', 'kiryzsu@gmail.com')->first();

if ($user) {
    $user->password = Hash::make('admin123');
    $user->save();
    
    echo "Password reset successfully!\n";
    echo "Email: {$user->email}\n";
    echo "Password: admin123\n";
} else {
    echo "User not found!\n";
}
