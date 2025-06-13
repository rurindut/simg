<?php

namespace App\Filament\Resources\AnggotaResource\Pages;

use App\Filament\Resources\AnggotaResource;
use App\Models\Anggota;
use App\Models\Atestasi;
// use Filament\Actions;
// use Filament\Tables\Table;
use Filament\Resources\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Log;

class EditAnggotaAtestasi extends Page implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    protected static string $resource = AnggotaResource::class;
    protected static string $view = 'filament.resources.anggota-resource.pages.edit-anggota-atestasi';
    protected static ?string $slug = '{record}/edit/data-atestasi';

    public $record;
    public ?array $formData = [];

    public function mount(Anggota $record): void
    {
        $this->record = $record;
    }

    public function getTitle(): string
    {
        return 'Data Atestasi';
    }

    public function getBreadcrumbs(): array
    {
        return [
            AnggotaResource::getUrl('index') => 'Anggota',
            AnggotaResource::getUrl('edit', ['record' => $this->record]) => 'Data Pribadi',
            url()->current() => 'Data Atestasi',
        ];
    }

    protected function getTableQuery(): Builder
    {
        return Atestasi::query()->where('anggota_id', $this->record->id);
    }

    protected function getTableColumns(): array
    {
        return [
            \Filament\Tables\Columns\TextColumn::make('tanggal'),
            \Filament\Tables\Columns\TextColumn::make('tipe'),
            \Filament\Tables\Columns\TextColumn::make('gereja_dari')->label('Gereja Asal'),
            \Filament\Tables\Columns\TextColumn::make('alamat_asal'),
            \Filament\Tables\Columns\TextColumn::make('gereja_tujuan'),
            \Filament\Tables\Columns\TextColumn::make('alamat_tujuan'),
            \Filament\Tables\Columns\TextColumn::make('nomor_surat'),
            \Filament\Tables\Columns\TextColumn::make('alasan'),
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            \Filament\Tables\Actions\CreateAction::make()
                ->form(AnggotaResource::dataAtestasiForm())
                ->mutateFormDataUsing(fn (array $data) => [
                    ...$data,
                    'anggota_id' => $this->record->id,
                ]),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            \Filament\Tables\Actions\EditAction::make()
                ->form(AnggotaResource::dataAtestasiForm()),
            \Filament\Tables\Actions\DeleteAction::make(),
        ];
    }

}
