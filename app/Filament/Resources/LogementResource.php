<?php

namespace App\Filament\Resources;

use App\Exports\LogementsExport;
use App\Filament\Resources\LogementResource\Pages;
use App\Filament\Resources\LogementResource\RelationManagers;
use App\Models\Agence;
use App\Models\Cite;
use App\Models\Logement;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Contracts\Database\ModelIdentifier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class LogementResource extends Resource
{
    protected static ?string $model = Logement::class;
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-in';

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        $agenceIds = Agence::where('user_id', $user->getAuthIdentifier())->pluck('id'); // récupère l'ID de l'agence de l'utilisateur connecté
        $defaultAgenceId = $agenceIds->first();
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    TextInput::make('numero_logement')
                        ->label('Numéro')
                        ->required()
                        ->regex('^PI-(?<segment1>[A-Z]+)-(?<segment2>[0-9]{3})^')
                        ->unique(ignoreRecord: true)
                        ->minLength(4)
                        ->placeholder('Entrez le numéro du logement ici...')
                        ->maxLength(20),
                    TextInput::make('prix_logement')
                        ->required()
                        ->label('Prix')
                        ->regex('^([1|2|3|4|5|6|7|8|9])\d{4,6}^')
                        ->suffix('Ariary')
                        ->minLength(5)
                        ->placeholder('Entrez le prix du logement ici...')
                        ->maxLength(7),
                    Forms\Components\Select::make('cite_id')
                        ->required()
                        ->relationship('cites', 'nom_cite', fn (Builder $query) => $query->where('agence_id','=',$defaultAgenceId)->select('cites.id','nom_cite')),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero_logement')
                    ->label('Numéro')
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('prix_logement')
                    ->label('Prix du Logement')
                    ->suffix(' Ariary')
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('cites.nom_cite')
                    ->label('Nom du cite')
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
                Tables\Actions\DeleteBulkAction::make(),Tables\Actions\BulkAction::make('export')
                    ->label('Exporter la selection')
                    ->icon('heroicon-o-document-download')
                    ->action(fn (Collection $records) => (new LogementsExport($records))->download('logements.xlsx')),
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
            'index' => Pages\ListLogements::route('/'),
            'create' => Pages\CreateLogement::route('/create'),
            'edit' => Pages\EditLogement::route('/{record}/edit'),
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
        return parent::getEloquentQuery()->whereHas('cites', function($query) use($defaultAgenceId) {
            $query->where('agence_id', '=', $defaultAgenceId);
        });
    }
}
