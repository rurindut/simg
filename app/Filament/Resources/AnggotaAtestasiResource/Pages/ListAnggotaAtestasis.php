<?php

namespace App\Filament\Resources\AnggotaAtestasiResource\Pages;

use App\Filament\Resources\AnggotaAtestasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnggotaAtestasis extends ListRecords
{
    protected static string $resource = AnggotaAtestasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
