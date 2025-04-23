<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motif extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'duration_minutes'];

    public function plageHoraires() {
        return $this->belongsToMany(PlageHoraire::class, 'plage_horaire_motif');
    }
}
