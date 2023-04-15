<?php

namespace App\Filament\Resources\AgenceResource\Pages;

use App\Filament\Resources\AgenceResource;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgence extends EditRecord
{
    protected static string $resource = AgenceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->title('Modification réussie')
            ->success()
            ->body('**Agence** modifiée avec succès')
            ->send();
    }
}
