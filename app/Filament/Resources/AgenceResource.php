<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgenceResource\Pages;
use App\Filament\Resources\AgenceResource\RelationManagers;
use App\Filament\Resources\AgenceResource\Widgets\AgenceStatsOverview;
use App\Models\Agence;
use App\Models\Cite;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use mysql_xdevapi\Warning;
use function League\Uri\parse;

class AgenceResource extends Resource
{
    protected static ?string $model = Agence::class;
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-office-building';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    TextInput::make('nom_agence')
                        ->minLength(3)
                        ->regex('^[a-zA-Z0-9éèêëàçôûüÿñæœ\s\-\'.,]^')
                        ->required()
                        ->placeholder('Entrez le nom de l\'agence ici...')
                        ->maxLength(50),
                    TextInput::make('libelle_agence')
                        ->unique(ignoreRecord: true)
                        ->required()
                        ->regex('^[a-zA-Z0-9éèêëàçôûüÿñæœ\s\-\'.,]^')
                        ->minLength(10)
                        ->placeholder('Entrez le libelle de l\'agence ici...')
                        ->maxLength(40),
                    TextInput::make('numero_agence')
                        ->unique(ignoreRecord: true)
                        ->required()
                        ->regex('^(03[2|3|4|8])\s\d{2}\s\d{3}\s\d{2}^')
                        ->minLength(13)
                        ->placeholder('Entrez le numéro de l\'agence ici...')
                        ->maxLength(13),
                    TextInput::make('lieu_agence')
                        ->required()
                        ->minLength(4)
                        ->regex('^[a-zA-Z0-9éèêëàçôûüÿñæœ\s\-\'.,]^')
                        ->placeholder('Entrez le lieu de l\'agence ici...')
                        ->maxLength(40),
                    Forms\Components\Select::make('user_id')
                        ->unique(ignoreRecord: true)
                        ->required()
                        ->relationship('users','email', fn (Builder $query) => $query->where('id','!=','1'))
                        ->preload(),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom_agence')
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('libelle_agence')
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('numero_agence')
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('lieu_agence')
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->sortable(),
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
            'index' => Pages\ListAgences::route('/'),
            'create' => Pages\CreateAgence::route('/create'),
            'edit' => Pages\EditAgence::route('/{record}/edit'),
        ];
    }
    public static function getWidgets(): array
    {
        if (auth()->check()) {
        $user = Auth::user();
        if ($user->getAuthIdentifier()!=1) {
            return [
                AgenceStatsOverview::class,
            ];
        }
        }return [];
    }

}
