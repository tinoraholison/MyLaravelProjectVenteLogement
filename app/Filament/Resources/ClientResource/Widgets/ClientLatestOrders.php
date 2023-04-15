<?php

namespace App\Filament\Resources\ClientResource\Widgets;

use App\Models\Agence;
use App\Models\Client;
use Closure;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ClientLatestOrders extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Information supplémentaire des clients';
    protected function getTableQuery(): Builder
    {
        if (method_exists(ClientLatestOrders::class, 'getEloquentQuery')) {
            $user = Auth::user();
            if ($user->getAuthIdentifier()==1) {
                return parent::getEloquentQuery();
            }
            $agenceIds = Agence::where('user_id', $user->getAuthIdentifier())->pluck('id'); // récupère l'ID de l'agence de l'utilisateur connecté
            $defaultAgenceId = $agenceIds->first();
            return Client::query()->whereHas('logements.cites.agences', function($query) use($defaultAgenceId) {
                $query->where('agence_id', '=', $defaultAgenceId);
            });
        }
        // retourner une instance de l'objet Builder vide
        return Client::query();

    }

    protected function getTableColumns(): array
    {
        return [

            Tables\Columns\TextColumn::make('nom_client')
                ->label('Nom')
                ->sortable()->searchable(),
            Tables\Columns\TextColumn::make('prenom_client')
                ->label('Prénom')
                ->sortable()->searchable(),
            Tables\Columns\TextColumn::make('cin_client')
                ->label('CIN')
                ->sortable()->searchable(),
            Tables\Columns\TextColumn::make('adresse_client')
                ->label('Adresse')
                ->sortable()->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Créée le')
                ->dateTime('d/m/y H:i')->sortable(),
            Tables\Columns\TextColumn::make('updated_at')
                ->label('Mise à jour le')
                ->dateTime('d/m/y H:i')->sortable(),
        ];
    }
}
