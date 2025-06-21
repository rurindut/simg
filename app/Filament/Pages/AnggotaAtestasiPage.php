<?php

namespace App\Filament\Pages;

use App\Models\Anggota;
use App\Models\Atestasi;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AnggotaResource;

class AnggotaAtestasiPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-right-on-rectangle';
    protected static ?string $navigationLabel = 'Anggota Atestasi';
    protected static ?string $title = 'Anggota Atestasi';
    protected static ?string $slug = 'anggota-atestasi';
    protected static ?string $navigationGroup = 'Keanggotaan';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.anggota-atestasi-page';

    protected static ?string $model = \App\Models\Atestasi::class;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
            ->columns([
                TextColumn::make('anggota.nia')->label('NIA'),
                TextColumn::make('anggota.nama')->label('Nama Lengkap')->sortable(),
                TextColumn::make('anggota.nomor_hp')->label('No. HP')->sortable(),
                TextColumn::make('anggota.region.name')
                    ->label('Wilayah')
                    ->searchable(),
                TextColumn::make('anggota.cluster.name')
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
                TextColumn::make('anggota.status_jemaat')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'anggota' => 'success',
                        'simpatisan' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('anggota.usia')
                    ->label('Usia')
                    ->getStateUsing(function ($record) {
                        $anggota = $record->anggota;
                        return $anggota->tanggal_lahir
                            ? \Carbon\Carbon::parse($anggota->tanggal_lahir)->age . ' tahun'
                            : '-';
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn ($record) => AnggotaResource::getUrl('edit', ['record' => $record->anggota_id]))
                    ->icon('heroicon-m-pencil'),
            ]);
    }

    protected function getQuery(): Builder
    {
        return Atestasi::query()->whereHas('anggota', function ($query) {
            if (! auth()->user()?->is_super_admin) {
                $query->where('organization_id', auth()->user()->organization_id);
            }
        });
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->is_super_admin || auth()->user()?->can('view_anggota');
    }
}
