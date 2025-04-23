<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Address;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Central Clinic', 'East Clinic', 'West Center'] as $name) {
            Address::create(['name' => $name]);
        }
    }
}
