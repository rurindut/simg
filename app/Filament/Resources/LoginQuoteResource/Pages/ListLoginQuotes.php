<?php

namespace App\Filament\Resources\LoginQuoteResource\Pages;

use App\Filament\Resources\LoginQuoteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoginQuotes extends ListRecords
{
    protected static string $resource = LoginQuoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
