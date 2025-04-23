<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, PlageHoraire, Motif, Address};

class PlageHoraireSeeder extends Seeder
{
    public function run(): void
    {
        $motifs = Motif::all();
        $addresses = Address::all();

        foreach (User::all() as $user) {
            foreach ($addresses as $address) {
                $plage = PlageHoraire::create([
                    'address_id' => $address->id,
                    'week_days' => ['tuesday', 'thursday','wednesday','friday','saturday','sunday','monday'],
                    'start_time' => '08:00',
                    'end_time' => '12:00',
                    'date' => null
                ]);

                $plage->motifs()->attach(
                    $motifs->random(rand(1, $motifs->count()))->pluck('id')
                );
            }
        }
    }
}
