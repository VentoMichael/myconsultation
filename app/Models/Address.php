<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function plageHoraires() {
        return $this->hasMany(PlageHoraire::class);
    }
    public function rendezVous() {
        return $this->hasMany(RendezVous::class);
    }
}
