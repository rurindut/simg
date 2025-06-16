<?php

namespace App\Filament\Resources\UltahKelahiranResource\Pages;

use App\Filament\Resources\UltahKelahiranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUltahKelahiran extends EditRecord
{
    protected static string $resource = UltahKelahiranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
