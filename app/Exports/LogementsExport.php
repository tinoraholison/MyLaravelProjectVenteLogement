<?php

namespace App\Exports;

use App\Models\Logement;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LogementsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    protected $records;

    public function __construct(Collection $records)
    {
        $this->records = $records;
    }

    public function query()
    {
        return Logement::whereIn('id', $this->records->pluck('id'));
    }

    public function headings(): array
    {
        // Retourne les en-têtes de colonne pour le fichier PDF
        return ['Numéro du Logement', 'Prix', 'Nom du Cité'];
    }

    public function map($logement): array
    {
        // Récupère le nom du cite associé à ce logement
        $nomCite = optional($logement->cites)->nom_cite ?? 'N/A';
        // Retourne les données de chaque logement à inclure dans le fichier PDF
        return [
            $logement->numero_logement,
            $logement->prix_logement,
            $nomCite, // Inclut le nom cité dans le tableau de données
        ];
    }
}
