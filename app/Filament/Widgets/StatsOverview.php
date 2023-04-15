<?php

namespace App\Filament\Widgets;

use App\Models\Agence;
use App\Models\Cite;
use App\Models\Client;
use App\Models\Logement;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{

    protected static ?int $sort = 1;
    protected function getCards(): array
    {
        $totalAgences = Agence::count();
        $totalCites = Cite::count();
        $totalLogements = Logement::count();
        $totalClients = Client::count();
        return [
            Card::make('Total des Agences', $totalAgences)
                ->chart([19, 2, 10, 19, 8, 4, 12])
                ->color('success'),
            Card::make('Total des Cités', $totalCites)
                ->chart([7, 15, 19, 3, 15, 1, 19])
                ->color('primary'),
            Card::make('Total des Logements', $totalLogements)
                ->chart([19, 1, 10, 3, 15, 4, 17])
                ->color('danger'),
            Card::make('Total des Clients', $totalClients)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('secondary'),
        ];
    }
}
