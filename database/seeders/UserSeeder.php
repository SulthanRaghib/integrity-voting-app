<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update the Filament admin account.
        // The User model casts 'password' => 'hashed', so we provide the plain password.
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => 'password', // Plain text, handled by 'hashed' cast
                'email_verified_at' => now(),
            ]
        );
    }
}
