<?php

namespace App\Filament\Resources\AgenceResource\Pages;

use App\Filament\Resources\AgenceResource;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAgence extends CreateRecord
{
    protected static string $resource = AgenceResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->title('Insertion réussie')
            ->success()
            ->body('**Agence** ajoutée avec succès')
            ->send();
    }
}
