<?php

namespace App\Filament\Resources\SukuResource\Pages;

use App\Filament\Resources\SukuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSukus extends ListRecords
{
    protected static string $resource = SukuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
