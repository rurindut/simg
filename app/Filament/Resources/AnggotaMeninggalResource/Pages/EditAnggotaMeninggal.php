<?php

namespace App\Filament\Resources\AnggotaMeninggalResource\Pages;

use App\Filament\Resources\AnggotaMeninggalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnggotaMeninggal extends EditRecord
{
    protected static string $resource = AnggotaMeninggalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
