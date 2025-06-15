<?php

namespace App\Filament\Resources\AnggotaResource\Pages;

use App\Filament\Resources\AnggotaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAnggota extends ViewRecord
{
    protected static string $resource = AnggotaResource::class;

    protected static string $view = 'filament.resources.anggota-resource.pages.view-anggota';

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\EditAction::make(),
    //     ];
    // }
}
