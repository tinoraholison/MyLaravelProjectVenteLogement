<?php

namespace App\Filament\Resources\CiteResource\Pages;

use App\Filament\Resources\CiteResource;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCite extends EditRecord
{
    protected static string $resource = CiteResource::class;

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
            ->body('**Cité** modifiée avec succès')
            ->send();
    }
}
