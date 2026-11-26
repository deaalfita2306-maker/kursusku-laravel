<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminSeeder::class);

        User::firstOrCreate(
            ['email' => 'user@kursusku.test'],
            [
                'name' => 'Peserta KursusKu',
                'username' => 'peserta',
                'password' => Hash::make('KursusKu#User2026!'),
                'role' => 'user',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->call([
            CourseSeeder::class,
            ParticipantSeeder::class,
        ]);
    }
}
