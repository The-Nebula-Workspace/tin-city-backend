<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'metrobuscity@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Metro$247'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
