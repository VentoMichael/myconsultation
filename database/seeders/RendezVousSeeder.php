<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{RendezVous, Address};
use Carbon\Carbon;

class RendezVousSeeder extends Seeder
{
    public function run(): void
    {
        $start = Carbon::now()->addDays(1)->setTime(9, 0);
        $end = $start->copy()->addMinutes(30);

        $addresses = Address::all();
        $i = 0;
        foreach ($addresses as $address) {
            if ($i < 2) {
                RendezVous::create([
                    'start' => $start,
                    'end' => $end,
                    'address_id' => $address->id,
                ]);
            }
            $i++;
        }
    }
}
