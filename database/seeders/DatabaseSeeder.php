<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dormitory;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample dormitories
        $dormitories = [
            'หอ 1 (หญิง)',
            'หอ 2 (หญิง)',
            'หอ 3 (ชาย)',
            'หอ 4 (ชาย)',
            'หอ 5 (ชาย)',
        ];

        foreach ($dormitories as $dormName) {
            Dormitory::create(['name' => $dormName]);
        }

        $this->command->info('Seeded 5 dormitories successfully!');
    }
}
