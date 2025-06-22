<?php

namespace App\Filament\Resources\KomselResource\Pages;

use App\Filament\Resources\KomselResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKomsel extends EditRecord
{
    protected static string $resource = KomselResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
