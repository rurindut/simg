<?php

namespace App\Filament\Resources\UltahPernikahanResource\Pages;

use App\Filament\Resources\UltahPernikahanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUltahPernikahans extends ListRecords
{
    protected static string $resource = UltahPernikahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
