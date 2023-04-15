<?php

namespace App\Filament\Resources\CiteResource\Pages;

use App\Filament\Resources\CiteResource;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCite extends CreateRecord
{
    protected static string $resource = CiteResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->title('Insertion réussie')
            ->success()
            ->body('**Cité** ajoutée avec succès')
            ->send();
    }
}
