<?php

namespace App\Filament\Resources\AnggotaResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\AnggotaResource;

class ViewAnggota extends ViewRecord
{
    protected static string $resource = AnggotaResource::class;

    protected static string $view = 'filament.resources.anggota-resource.pages.view-anggota';

    public function getViewData(): array
    {
        return [
            'record' => $this->record,
            'baptis' => $this->record->baptis,
            'atestasi' => $this->record->atestasi,
            'keluarga' => $this->record->keluarga,
            'aktivitas' => $this->record->aktivitas,
        ];
    }
}
