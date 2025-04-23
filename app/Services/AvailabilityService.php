<?php 

namespace App\Services;

use App\Models\{PlageHoraire, RendezVous, Indisponibilite, Motif};
use Carbon\Carbon;

class AvailabilityService
{
    public function getAllDisponibilites(string $doctorName): array
    {
        $results = [];
        $motifs = \App\Models\Motif::all();
        $doctor = \App\Models\User::where('name', $doctorName)->first();
        if (!$doctor) return [];
        $addresses = $doctor->addresses;
        $now = \Carbon\Carbon::now();

        foreach ($addresses as $address) {
            foreach ($motifs as $motif) {
                for ($i = 0; $i < 30; $i++) {
                    $date = $now->copy()->addDays($i)->startOfDay();
                    $dayName = strtolower($date->format('l'));
                    $plages = \App\Models\PlageHoraire::with('motifs')
                        ->where('address_id', $address->id)
                        ->where(function ($q) use ($date, $dayName) {
                            $q->whereJsonContains('week_days', $dayName)
                              ->orWhereDate('date', $date);
                        })
                        ->get()
                        ->filter(fn($plage) => $plage->motifs->contains('id', $motif->id));
                    foreach ($plages as $plage) {
                        $start = \Carbon\Carbon::parse($date->format('Y-m-d') . ' ' . $plage->start_time);
                        $end = \Carbon\Carbon::parse($date->format('Y-m-d') . ' ' . $plage->end_time);
                        while ($start->copy()->addMinutes($motif->duration_minutes)->lte($end)) {
                            $slotEnd = $start->copy()->addMinutes($motif->duration_minutes);
                            $overlaps = \App\Models\RendezVous::where('address_id', $address->id)
                                ->where(function ($q) use ($start, $slotEnd) {
                                    $q->whereBetween('start', [$start, $slotEnd])
                                      ->orWhereBetween('end', [$start, $slotEnd])
                                      ->orWhere(function ($q) use ($start, $slotEnd) {
                                          $q->where('start', '<=', $start)
                                            ->where('end', '>=', $slotEnd);
                                      });
                                })->exists();
                            $indispo = \App\Models\Indisponibilite::where('address_id', $address->id)
                                ->where(function ($q) use ($start, $slotEnd) {
                                    $q->whereBetween('start', [$start, $slotEnd])
                                      ->orWhereBetween('end', [$start, $slotEnd])
                                      ->orWhere(function ($q) use ($start, $slotEnd) {
                                          $q->where('start', '<=', $start)
                                            ->where('end', '>=', $slotEnd);
                                      });
                                })->exists();
                            if (!$overlaps && !$indispo && $start->gte($now)) {
                                $results[] = [
                                    'datetime' => $start->format('Y-m-d H:i'),
                                    'doctor' => $doctor->name,
                                    'address' => $address->name,
                                    'motif' => $motif->name
                                ];
                            }
                            $start->addMinutes(5);
                        }
                    }
                }
            }
        }
        return $results;
    }

    public function getNextDisponibilite(int $addressId, int $motifId): ?Carbon
    {
        $motif = Motif::findOrFail($motifId);
        $now = Carbon::now();

        for ($i = 0; $i < 30; $i++) {
            $date = $now->copy()->addDays($i)->startOfDay();
            $dayName = strtolower($date->format('l'));
            $plages = PlageHoraire::with('motifs')
                ->where('address_id', $addressId)
                ->where(function ($q) use ($date, $dayName) {
                    $q->whereJsonContains('week_days', $dayName)
                      ->orWhereDate('date', $date);
                })
                ->get();
            foreach ($plages as $plage) {
                $start = Carbon::parse($date->format('Y-m-d') . ' ' . $plage->start_time);
                $end = Carbon::parse($date->format('Y-m-d') . ' ' . $plage->end_time);
                while ($start->copy()->addMinutes($motif->duration_minutes)->lte($end)) {
                    $slotEnd = $start->copy()->addMinutes($motif->duree_minutes);

                    $overlaps = RendezVous::where('address_id', $addressId)
                        ->where(function ($q) use ($start, $slotEnd) {
                            $q->whereBetween('start', [$start, $slotEnd])
                              ->orWhereBetween('end', [$start, $slotEnd])
                              ->orWhere(function ($q) use ($start, $slotEnd) {
                                  $q->where('start', '<=', $start)
                                    ->where('end', '>=', $slotEnd);
                              });
                        })->exists();

                    $indispo = Indisponibilite::where(function ($q) use ($start, $slotEnd) {
                        $q->whereBetween('start', [$start, $slotEnd])
                          ->orWhereBetween('end', [$start, $slotEnd])
                          ->orWhere(function ($q) use ($start, $slotEnd) {
                              $q->where('start', '<=', $start)
                                ->where('end', '>=', $slotEnd);
                          });
                    })->exists();

                    if (!$overlaps && !$indispo && $start->gte($now)) {
                        return $start;
                    }

                    $start->addMinutes(5); 
                }
            }
        }

        return null;
    }
}
