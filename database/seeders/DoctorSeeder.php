<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Address;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Cabinet Central', 'Clinique Est', 'Centre Ouest'] as $name) {

            User::factory()->create([
                'name' => "Dr $name",
                'email' => strtolower(str_replace(' ', '', $name)) . '@myconsultation.be',
            ]);
        }
    }
}
