<?php

namespace App\Filament\Resources\LoginQuoteResource\Pages;

use App\Filament\Resources\LoginQuoteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoginQuote extends EditRecord
{
    protected static string $resource = LoginQuoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
