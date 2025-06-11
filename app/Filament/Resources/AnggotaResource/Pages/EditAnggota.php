<?php

namespace App\Filament\Resources\AnggotaResource\Pages;

use App\Filament\Resources\AnggotaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnggota extends EditRecord
{
    protected static string $resource = AnggotaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('data_baptis')
                ->label('Data Baptis')
                ->url(fn () => route('filament.admin.resources.anggota.edit-baptis', ['record' => $this->record])),
            
            // Actions\Action::make('data_atestasi')
            //     ->label('Data Atestasi')
            //     ->url(fn () => route('filament.admin.resources.anggota.edit-atestasi', ['record' => $this->record])),
            
            Actions\DeleteAction::make(),
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
}
