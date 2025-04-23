<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RendezVous extends Model
{
    use HasFactory;
    protected $table = 'rendez_vous';
    protected $fillable = ['start', 'end', 'address_id'];
    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];
    public function address() { return $this->belongsTo(Address::class); }
}
