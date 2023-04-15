<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logement extends Model
{
    use HasFactory;
    protected $fillable = [
        'numero_logement',
        'prix_logement',
        'cite_id',
    ];
    public function cites()
    {
        return $this->belongsTo(Cite::class,'cite_id');
    }
    public function clients()
    {
        return $this->hasMany(Client::class,'logement_id');
    }
}
