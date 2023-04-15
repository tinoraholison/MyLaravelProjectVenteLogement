<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cite extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_cite',
        'libelle_cite',
        'numero_terrain',
        'superficie_terrain',
        'agence_id',
    ];
    public function agences()
    {
        return $this->belongsTo(Agence::class,'agence_id');
    }
    public function cites()
    {
        return $this->hasMany(Cite::class,'cite_id');
    }
}
