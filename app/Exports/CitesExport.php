<?php

namespace App\Exports;

use App\Models\Cite;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CitesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    protected $records;

    public function __construct(Collection $records)
    {
        $this->records = $records;
    }

    public function query()
    {
        return Cite::whereIn('id', $this->records->pluck('id'));
    }

    public function headings(): array
    {
        // Retourne les en-têtes de colonne pour le fichier PDF
        return ['Nom du Cité', 'Libellé', 'Numéro du Terrain','Superficie(m²)','Agence'];
    }

    public function map($cite): array
    {
        // Récupère le nom de l'agence associé à ce cite
        $nomAgence = optional($cite->agences)->nom_agence ?? 'N/A';
        // Retourne les données de chaque cite à inclure dans le fichier PDF
        return [
            $cite->nom_cite,
            $cite->libelle_cite,
            $cite->numero_terrain,
            $cite->superficie_terrain,
            $nomAgence, // Inclut le nom de l'agence dans le tableau de données
        ];
    }
}
