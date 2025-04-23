<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Motif;

class MotifSeeder extends Seeder
{
    public function run(): void
    {
        Motif::insert([
            ['name' => 'Consultation', 'duration_minutes' => 30],
            ['name' => 'Suivi', 'duration_minutes' => 15],
            ['name' => 'Urgence', 'duration_minutes' => 45],
        ]);
    }
}
