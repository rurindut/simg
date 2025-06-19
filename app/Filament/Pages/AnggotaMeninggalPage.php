<?php

namespace App\Filament\Pages;

use App\Models\Anggota;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AnggotaResource;

class AnggotaMeninggalPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-user-minus';
    protected static ?string $navigationLabel = 'Anggota Meninggal';
    protected static ?string $title = 'Anggota Meninggal';
    protected static ?string $slug = 'anggota-meninggal';
    protected static ?string $navigationGroup = 'Keanggotaan';
    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.anggota-meninggal-page';

    public static function canAccess(): bool
    {
        return auth()->user()?->is_super_admin || auth()->user()?->can('view_anggota');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
            ->columns([
                TextColumn::make('nia')->label('NIA'),
                TextColumn::make('nama')->label('Nama Lengkap')->sortable(),
                TextColumn::make('nomor_hp')->label('No. HP')->sortable(),
                TextColumn::make('region.name')
                    ->label('Wilayah')
                    ->searchable(),
                TextColumn::make('cluster.name')
                    ->label('Kelompok')
                    ->searchable(),
                TextColumn::make('keluarga')
                    ->label('Keluarga')
                    ->getStateUsing(function ($record) {
                        $anggota = $record->anggota;
                        if (! $anggota) return '-';
                        $pasangan = optional($anggota->pasangan)?->nama;
                        $jumlahAnak = $anggota->anaks()->count();
                        return ($pasangan ? 1 : 0) + $jumlahAnak;
                    }),
                TextColumn::make('status_jemaat')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'anggota' => 'success',
                        'simpatisan' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn ($record) => AnggotaResource::getUrl('edit', ['record' => $record->id]))
                    ->icon('heroicon-m-pencil'),
            ]);
    }

    protected function getQuery(): Builder
    {
        return Anggota::query()
            ->where('status_hidup', 'meninggal')
            ->when(! auth()->user()?->is_super_admin, function ($query) {
                $query->where('organization_id', auth()->user()->organization_id);
            });
    }
}
