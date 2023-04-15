<?php

namespace App\Filament\Resources\LogementResource\Pages;

use App\Filament\Resources\LogementResource;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLogement extends EditRecord
{
    protected static string $resource = LogementResource::class;

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
            ->body('**Logement** modifié avec succès')
            ->send();
    }
}
