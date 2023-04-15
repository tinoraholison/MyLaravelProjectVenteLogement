<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class DownloadController extends Controller
{
    public function download(Client $record)
    {
        $client = $record;
        $logements = $client->logements()->pluck('numero_logement')->unique();
        $logementsprix = $client->logements()->pluck('prix_logement')->unique();
        $cite = auth()->user()->agences()->cites()->nom_cite;
        $agence = auth()->user()->agence;

        $customer = new Buyer([
            'Nom du client'     => $client->nom_client,
            'Prénom du Client'  => $client->prenom_client,
            'Agence'            => $agence->nom_agence,
            'Logement'          => $logements,
            'Cité'              => $cite,
            'custom_fields'     => [
                'Type'          => $client->type_achat,
            ],
        ]);

        $totalPrice = $logements->sum('prix_logement');

        $item = (new InvoiceItem())->title('Achat chez '.$agence->nom_agence)
            ->pricePerUnit($totalPrice)
            ->quantity($logements->count());

        $invoice = Invoice::make()
            ->buyer($customer)
            ->addItem($item);

        return $invoice->stream();
    }
}
