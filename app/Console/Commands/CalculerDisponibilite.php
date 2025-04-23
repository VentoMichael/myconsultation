<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CalculerDisponibilite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calculer-disponibilite {doctor_name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $adresseId = 1;
        $motifId = 2;

        // php artisan app:calculer-disponibilite "Dr Clinique Est"

        $doctorName = $this->argument('doctor_name');
        $service = app(\App\Services\AvailabilityService::class);

        if ($doctorName) {
            $dispo = $service->getNextDisponibilite($adresseId, $motifId);
            $this->info('Prochaine disponibilité : ' . ($dispo ? $dispo->format('Y-m-d H:i') : 'Aucune trouvée'));
        } else {
            $doctors = \App\Models\User::all();
            $motifs = \App\Models\Motif::all();
            $addresses = \App\Models\Address::all();
            foreach ($doctors as $doctor) {
                foreach ($addresses as $address) {
                    foreach ($motifs as $motif) {
                        $dispo = $service->getNextDisponibilite($address->id, $motif->id);
                        if ($dispo) {
                            $this->info("Prochaine disponibilité : {$dispo->format('Y-m-d H:i')} | {$doctor->name} | {$address->name} | {$motif->name}");
                            return;
                        }
                    }
                }
            }
            $this->info('Aucune disponibilité trouvée');
        }
    }
}
