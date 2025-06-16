<?php

namespace App\Filament\Resources\AnggotaResource\Pages;

use App\Filament\Resources\AnggotaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnggota extends EditRecord
{
    protected static string $resource = AnggotaResource::class;
    protected static string $view = 'filament.resources.anggota-resource.pages.edit';

    protected function getHeaderActions(): array
    {
        return [
            // 
        ];
    }

    protected function getFormSchema(): array
    {
        return AnggotaResource::dataPribadiForm();
    }

    public function getTitle(): string
    {
        return 'Data Pribadi';
    }

    public function getBreadcrumbs(): array
    {
        return [
            AnggotaResource::getUrl('index') => 'Anggota',
            url()->current() => 'Data Pribadi',
        ];
    }
}
