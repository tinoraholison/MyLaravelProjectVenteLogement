<?php

namespace App\Filament\Resources\CiteResource\Pages;

use App\Filament\Resources\CiteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCites extends ListRecords
{
    protected static string $resource = CiteResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
