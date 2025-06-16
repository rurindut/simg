<?php

namespace App\Filament\Resources\AnggotaAtestasiResource\Pages;

use App\Filament\Resources\AnggotaAtestasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnggotaAtestasi extends EditRecord
{
    protected static string $resource = AnggotaAtestasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
