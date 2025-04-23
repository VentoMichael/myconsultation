<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Indisponibilite;
use Carbon\Carbon;

class IndisponibiliteSeeder extends Seeder
{
    public function run(): void
    {
        $start = Carbon::now()->addDays(2)->setTime(10, 0);
        $end = $start->copy()->addHour();

        Indisponibilite::create([
            'start' => $start,
            'end' => $end
        ]);
    }
}
