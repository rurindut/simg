<?php

namespace App\Filament\Resources\UltahPernikahanResource\Pages;

use App\Filament\Resources\UltahPernikahanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUltahPernikahan extends EditRecord
{
    protected static string $resource = UltahPernikahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
