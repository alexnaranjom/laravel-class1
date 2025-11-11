<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where('email', 'alex@example.com')->first();

if (! $user) {
    echo "User alex@example.com not found\n";
    exit(1);
}

$user->password = Hash::make('password');
$user->save();

echo "Password for alex@example.com reset to 'password'\n";
