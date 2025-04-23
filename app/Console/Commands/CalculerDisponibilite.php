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
    protected $signature = 'app:calculer-disponibilite {doctor_name?} {start_date?} {end_date?}';

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

        // php artisan app:calculer-disponibilite "Dr Cabinet Central" 2025-04-25 2025-04-28

        $doctorName = $this->argument('doctor_name');
        $startDate = $this->argument('start_date') ? \Carbon\Carbon::parse($this->argument('start_date')) : null;
        $endDate = $this->argument('end_date') ? \Carbon\Carbon::parse($this->argument('end_date')) : null;
        $service = app(\App\Services\AvailabilityService::class);

        $results = [];
        $doctors = $doctorName ? \App\Models\User::where('name', $doctorName)->get() : \App\Models\User::all();
        $motifs = \App\Models\Motif::all();
        $addresses = \App\Models\Address::all();
        foreach ($doctors as $doctor) {
            foreach ($addresses as $address) {
                foreach ($motifs as $motif) {
                    $dispo = $service->getNextDisponibilite($address->id, $motif->id, $startDate, $endDate);
                    if ($dispo) {
                        if ($startDate && $endDate) {
                            if ($dispo->between($startDate, $endDate)) {
                                $results[] = [
                                    'datetime' => $dispo->format('Y-m-d H:i'),
                                    'doctor' => $doctor->name,
                                    'address' => $address->name,
                                    'motif' => $motif->name
                                ];
                            }
                        } else {
                            $results[] = [
                                'datetime' => $dispo->format('Y-m-d H:i'),
                                'doctor' => $doctor->name,
                                'address' => $address->name,
                                'motif' => $motif->name
                            ];
                        }
                    }
                }
            }
        }
        if (empty($results)) {
            $this->info('Aucune disponibilité trouvée');
        } else {
            foreach ($results as $r) {
                $this->line("{$r['datetime']} | {$r['doctor']} | {$r['address']} | {$r['motif']}");
            }
        }
    }
}
