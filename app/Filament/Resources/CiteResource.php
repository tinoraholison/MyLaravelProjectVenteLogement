<?php

namespace App\Filament\Resources;

use App\Exports\CitesExport;
use App\Filament\Resources\CiteResource\Pages;
use App\Filament\Resources\CiteResource\RelationManagers;
use App\Models\Agence;
use App\Models\Cite;
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

class CiteResource extends Resource
{
    protected static ?string $model = Cite::class;
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-flag';
    protected static ?string $navigationLabel = 'Cités';

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        $agenceIds = Agence::where('user_id', $user->getAuthIdentifier())->pluck('id'); // récupère l'ID de l'agence de l'utilisateur connecté
        $defaultAgenceId = $agenceIds->first();

        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    TextInput::make('nom_cite')
                        ->unique(ignoreRecord: true)
                        ->required()
                        ->regex('^[a-zA-Z0-9éèêëàçôûüÿñæœ\s\-\'.,]^')
                        ->minLength(3)
                        ->placeholder('Entrez le nom de cité ici...')
                        ->maxLength(30),
                    TextInput::make('libelle_cite')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->regex('^[a-zA-Z0-9éèêëàçôûüÿñæœ\s\-\'.,]^')
                        ->minLength(5)
                        ->placeholder('Entrez le libelle du cité ici...')
                        ->maxLength(40),
                    TextInput::make('numero_terrain')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->regex('^[a-zA-Z0-9éèêëàçôûüÿñæœ\s\-\'.,]^')
                        ->minLength(10)
                        ->placeholder('Entrez le numéro du terrain ici...')
                        ->maxLength(40),
                    TextInput::make('superficie_terrain')
                        ->required()
                        ->suffix(' m²')
                        ->regex('^[0-9]^')
                        ->minLength(5)
                        ->placeholder('Entrez la superficie du terrain ici...')
                        ->maxLength(10),
                    Forms\Components\Select::make('agence_id')
                        ->required()
                        ->relationship('agences', 'nom_agence', fn (Builder $query) => $query->where('id','=',$defaultAgenceId)),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom_cite')
                    ->label('Nom')
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('libelle_cite')
                    ->label('Libellé')
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('numero_terrain')
                    ->label('Numéro_Terrain')
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('superficie_terrain')
                    ->label('Superficie')
                    ->suffix(' m²')
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créée le')
                    ->dateTime('d/m/y H:i')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Mise à jour le')
                    ->dateTime('d/m/y H:i')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('export')
                    ->label('Exporter la selection')
                    ->icon('heroicon-o-document-download')
                    ->action(fn (Collection $records) => (new CitesExport($records))->download('cites.xlsx')),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCites::route('/'),
            'create' => Pages\CreateCite::route('/create'),
            'edit' => Pages\EditCite::route('/{record}/edit'),
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
        return parent::getEloquentQuery()->where('agence_id','=',$defaultAgenceId);
    }
}
