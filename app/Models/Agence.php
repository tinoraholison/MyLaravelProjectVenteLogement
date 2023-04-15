<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agence extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_agence',
        'libelle_agence',
        'lieu_agence',
        'numero_agence',
        'user_id',
    ];
    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function cites()
    {
        return $this->hasMany(Cite::class,'agence_id');
    }
}
