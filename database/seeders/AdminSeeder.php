<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('ADMIN_SEED_PASSWORD', 'KursusKu#Admin2026!');

        User::firstOrCreate(
            ['email' => 'admin@kursusku.test'],
            [
                'name' => 'Administrator KursusKu',
                'username' => 'admin',
                'password' => Hash::make($password),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
