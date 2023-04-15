<?php

namespace App\Filament\Resources;

use App\Exports\ClientsExport;
use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Filament\Resources\ClientResource\Widgets\ClientLatestOrders;
use App\Models\Agence;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;
    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-cash';
    protected static ?string $navigationLabel = 'Achats/Clients';

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        $agenceIds = Agence::where('user_id', $user->getAuthIdentifier())->pluck('id'); // récupère l'ID de l'agence de l'utilisateur connecté
        $defaultAgenceId = $agenceIds->first();
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    TextInput::make('nom_client')
                        ->label('Nom')
                        ->regex('^[a-zA-Zàâçéèêëîïôûùüÿñæœ\s-]^')
                        ->required()
                        ->minLength(4)
                        ->placeholder('Entrez le nom du client ici...')
                        ->maxLength(40),
                    TextInput::make('prenom_client')
                        ->label('Prénom')
                        ->required()
                        ->minLength(2)
                        ->regex('^[a-zA-Zàâçéèêëîïôûùüÿñæœ\s-]^')
                        ->unique(ignoreRecord: true)
                        ->placeholder('Entrez son prénom ici...')
                        ->maxLength(50),
                    TextInput::make('cin_client')
                        ->label('CIN')
                        ->required()
                        ->regex('^[0-9]^')
                        ->unique(ignoreRecord: true)
                        ->minLength(12)
                        ->placeholder('Entrez son numéro de Carte d\'Identité nationale ici...')
                        ->maxLength(12),
                    TextInput::make('adresse_client')
                        ->label('Adresse')
                        ->required()
                        ->regex('^[a-zA-Z0-9éèêëàçôûüÿñæœ\s\-\'.,]^')
                        ->unique(ignoreRecord: true)
                        ->minLength(4)
                        ->placeholder('Entrez son adresse ici...')
                        ->maxLength(40),
                    TextInput::make('numero_client')
                        ->label('Numéro Téléphone')
                        ->required()
                        ->regex('^(03[2|3|4|8])\s\d{2}\s\d{3}\s\d{2}^')
                        ->unique(ignoreRecord: true)
                        ->minLength(13)
                        ->placeholder('Entrez son numéro de téléphone ici...')
                        ->maxLength(13),
                    TextInput::make('lieu_travail')
                        ->required()
                        ->label('Lieu de travail')
                        ->regex('^[a-zA-Z0-9éèêëàçôûüÿñæœ\s\-\'.,]^')
                        ->minLength(4)
                        ->placeholder('Entrez le lieu de travail du client ici...')
                        ->maxLength(40),
                    Forms\Components\DateTimePicker::make('date_achat')
                        ->label('Date d\'Achat du logement'),
                    Forms\Components\Select::make('type_achat')
                        ->required()
                        ->label('Type d\'Achat effectué')
                        ->options([
                            'À crédit'=>'À crédit',
                            'Au comptant'=>'Au comptant'
                        ]),
                    Forms\Components\Select::make('logement_id')
                        ->required()
                        ->relationship('logements', 'numero_logement', function ($query) use ($defaultAgenceId) {
                            $query->doesntHave('clients')
                                ->whereHas('cites.agences', function ($query) use ($defaultAgenceId) {
                                $query->where('agence_id', $defaultAgenceId);});
                        }),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom_client')
                    ->label('Nom')
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('prenom_client')
                    ->label('Prénom')
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('date_achat')
                    ->label('Date d\'Achat')
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('type_achat')
                    ->label('Achat à')
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('numero_client')
                    ->label('Téléphone')
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('lieu_travail')
                    ->label('Lieu de Travail')
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Mise à jour le')
                    ->dateTime('d/m/y H:i')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Facture')
                    ->icon('heroicon-o-document-download')
                    ->url(fn (Client $record) => route('client.pdf.download', $record))
                    ->color('success')
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('export')
                ->label('Exporter la selection')
                ->icon('heroicon-o-document-download')
                ->action(fn (Collection $records) => (new ClientsExport($records))->download('clients.xlsx')),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        if ($user->getAuthIdentifier()==1) {
            return parent::getEloquentQuery();
        }
        $agenceIds = Agence::where('user_id', $user->getAuthIdentifier())->pluck('id'); // récupère l'ID de l'agence de l'utilisateur connecté
        $defaultAgenceId = $agenceIds->first();
        return parent::getEloquentQuery()->whereHas('logements.cites.agences', function($query) use($defaultAgenceId) {
            $query->where('agence_id', '=', $defaultAgenceId);
        });
    }

    public static function getWidgets(): array
    {
        if (!method_exists(ClientLatestOrders::class, 'getEloquentQuery')) {
            return [];
        }

        return [
            ClientLatestOrders::class,
        ];
    }
}
