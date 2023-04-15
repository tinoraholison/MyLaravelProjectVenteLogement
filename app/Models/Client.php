<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_client',
        'prenom_client',
        'cin_client',
        'adresse_client',
        'numero_client',
        'lieu_travail',
        'date_achat',
        'type_achat',
        'logement_id',
    ];

    public function logements()
    {
        return $this->belongsTo(Logement::class,'logement_id');
    }

}
