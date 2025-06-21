<?php

namespace App\Filament\Resources\AnggotaResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\AnggotaResource;
use Illuminate\View\View;

class ViewAnggota extends ViewRecord
{
    protected static string $resource = AnggotaResource::class;

    protected static string $view = 'filament.resources.anggota-resource.pages.view-anggota';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit')
                ->label('Edit')
                ->url(fn () => static::getResource()::getUrl('edit', ['record' => $this->record]))
                ->icon('heroicon-m-pencil-square')
                ->button()
                ->color('primary'),
            Action::make('print')
                ->label('Cetak')
                ->icon('heroicon-m-printer')
                ->url(fn () => route('anggota.cetak', ['record' => $this->record]))
                ->openUrlInNewTab()
                ->color('gray'),
        ];
    }

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

    public function getTitle(): string
    {
        return 'Detail Anggota';
    }
}
