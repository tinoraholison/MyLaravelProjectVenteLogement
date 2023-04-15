<?php

namespace App\Filament\Resources\AgenceResource\Pages;

use App\Filament\Resources\AgenceResource;
use App\Filament\Resources\AgenceResource\Widgets\AgenceStatsOverview;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListAgences extends ListRecords
{
    protected static string $resource = AgenceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        if (auth()->check()) {
            $user = Auth::user();
            if ($user->getAuthIdentifier()!=1) {
                return [
                    AgenceResource\Widgets\AgenceStatsOverview::class,
                ];
            }
        }return [];
    }
}
