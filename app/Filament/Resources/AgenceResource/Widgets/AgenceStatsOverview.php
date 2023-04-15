<?php

namespace App\Filament\Resources\AgenceResource\Widgets;

use App\Models\Agence;
use App\Models\Cite;
use App\Models\Client;
use App\Models\Logement;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\Auth;

class AgenceStatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $citeCount = 0;
        $logementCount = 0;
        $clientCount = 0;

        if (auth()->check()) {
            $user = Auth::user();
            $agenceIds = Agence::where('user_id', $user->getAuthIdentifier())->pluck('id');
            $agencenom = Agence::where('user_id', $user->getAuthIdentifier())->pluck('nom_agence')->first();
            $agenceId = $agenceIds->first();
            $citeCount = Cite::where('agence_id', $agenceId)->count();
            $logementCount = Logement::whereIn('cite_id', function ($query) use ($agenceId) {
                $query->select('id')->from('cites')->where('agence_id', $agenceId);
            })->count();
            $clientCount = Client::whereIn('logement_id', function ($query) use ($agenceId) {
                $query->select('id')->from('logements')->whereIn('cite_id', function ($query) use ($agenceId) {
                    $query->select('id')->from('cites')->where('agence_id', $agenceId);
                });
            })->count();
        }

        return [
            Card::make('Nombre de cités', $citeCount)
                ->description($agencenom)
                ->descriptionIcon('heroicon-s-office-building')
                ->chart([7, 2, 15, 10, 15, 4, 17])
                ->color('success'),
            Card::make('Nombre de logements', $logementCount)
                ->description('De toutes les cités de '. $agencenom)
                ->descriptionIcon('heroicon-s-home')
                ->chart([19, 2, 10, 3, 15, 4, 17])
                ->color('primary'),
            Card::make('Nombre de clients', $clientCount)
                ->description('Total des clients de '. $agencenom)
                ->descriptionIcon('heroicon-s-users')
                ->chart([7, 2, 17, 3, 10, 4, 12])
                ->color('success'),
        ];
    }
}
