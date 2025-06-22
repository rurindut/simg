<?php

namespace App\Filament\Resources\DaftarPelayananResource\Pages;

use App\Filament\Resources\DaftarPelayananResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDaftarPelayanan extends EditRecord
{
    protected static string $resource = DaftarPelayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
