<?php

namespace App\Exports;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClientsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    protected $records;

    public function __construct(Collection $records)
    {
        $this->records = $records;
    }

    public function query()
    {
        return Client::whereIn('id', $this->records->pluck('id'));
    }

    public function headings(): array
    {
        // Retourne les en-têtes de colonne pour le fichier PDF
        return ['ID', 'Nom', 'Prénom', 'CIN','Adresse','Téléphone','Lieu de Travail','Data d\'Achat','Type d\'Achat','Logement'];
    }

    public function map($client): array
    {
        // Récupère le numéro de logement associé à ce client
        $numeroLogement = optional($client->logements)->numero_logement ?? 'N/A';
        // Retourne les données de chaque client à inclure dans le fichier PDF
        return [
            $client->id,
            $client->nom_client,
            $client->prenom_client,
            $client->cin_client,
            $client->adresse_client,
            $client->numero_client,
            $client->lieu_travail,
            $client->date_achat,
            $client->type_achat,
            $numeroLogement, // Inclut le numéro de logement dans le tableau de données
        ];
    }
}
