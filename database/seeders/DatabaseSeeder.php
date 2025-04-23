<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{Address, Motif, PlageHoraire, RendezVous, Indisponibilite};

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    $this->call([
        AddressSeeder::class,
        DoctorSeeder::class,
        MotifSeeder::class,
        PlageHoraireSeeder::class,
        RendezVousSeeder::class,
        IndisponibiliteSeeder::class,
    ]);
}

}
