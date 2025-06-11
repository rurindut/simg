<?php

namespace App\Filament\Resources\LoginQuoteResource\Pages;

use App\Filament\Resources\LoginQuoteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLoginQuote extends CreateRecord
{
    protected static string $resource = LoginQuoteResource::class;
}
