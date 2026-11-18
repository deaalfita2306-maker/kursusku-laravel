<?php

namespace Database\Seeders;

use App\Models\Participant;
use Illuminate\Database\Seeder;

class ParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $participants = [
            [
                'email' => 'alya@example.test',
                'name' => 'Alya Rahma',
                'phone' => '081200000001',
            ],
            [
                'email' => 'bima@example.test',
                'name' => 'Bima Putra',
                'phone' => '081200000002',
            ],
            [
                'email' => 'citra@example.test',
                'name' => 'Citra Nabila',
                'phone' => null,
            ],
        ];

        foreach ($participants as $p) {
            Participant::firstOrCreate(
                ['email' => $p['email']],
                $p
            );
        }
    }
}
