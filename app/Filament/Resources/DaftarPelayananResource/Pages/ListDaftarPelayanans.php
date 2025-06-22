<?php

namespace App\Filament\Resources\DaftarPelayananResource\Pages;

use App\Filament\Resources\DaftarPelayananResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDaftarPelayanans extends ListRecords
{
    protected static string $resource = DaftarPelayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
