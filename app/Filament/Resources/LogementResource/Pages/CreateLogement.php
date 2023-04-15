<?php

namespace App\Filament\Resources\LogementResource\Pages;

use App\Filament\Resources\LogementResource;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLogement extends CreateRecord
{
    protected static string $resource = LogementResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->title('Insertion réussie')
            ->success()
            ->body('**Logement** ajouté avec succès')
            ->send();
    }
}
