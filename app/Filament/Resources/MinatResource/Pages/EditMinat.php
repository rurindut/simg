<?php

namespace App\Filament\Resources\MinatResource\Pages;

use App\Filament\Resources\MinatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMinat extends EditRecord
{
    protected static string $resource = MinatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
