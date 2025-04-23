<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlageHoraire extends Model
{
    use HasFactory;
    protected $fillable = [
        'address_id', 'week_days', 'date', 'start_time', 'end_time'
    ];
    protected $casts = [
        'week_days' => 'array',
        'date' => 'date',
    ];
    public function address() { return $this->belongsTo(Address::class); }
    public function motifs() { return $this->belongsToMany(Motif::class, 'plage_horaire_motif'); }
}
