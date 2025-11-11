<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'alex@example.com',
        ], [
            'name' => 'Alex',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);
    }
}
