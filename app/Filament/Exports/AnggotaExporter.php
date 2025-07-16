<?php

namespace App\Filament\Exports;

use App\Models\Anggota;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Carbon\Carbon;

class AnggotaExporter extends Exporter
{
    protected static ?string $model = Anggota::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('nia')
                ->label('NIA'),

            ExportColumn::make('nama')
                ->label('Nama Lengkap'),

            ExportColumn::make('nomor_hp')
                ->label('No. HP'),

            ExportColumn::make('region_name')
                ->label('Wilayah')
                ->getStateUsing(fn (Anggota $record): ?string => $record->region->name ?? null),

            ExportColumn::make('cluster_name')
                ->label('Kelompok')
                ->getStateUsing(fn (Anggota $record): ?string => $record->cluster->name ?? null),

            ExportColumn::make('jumlah_keluarga')
                ->label('Keluarga')
                ->getStateUsing(function (Anggota $record) {
                    $pasangan = optional($record->pasangan)?->nama;
                    $jumlahAnak = $record->anaks()->count();
                    return ($pasangan ? 1 : 0) + $jumlahAnak;
                }),

            ExportColumn::make('usia')
                ->label('Usia')
                ->getStateUsing(function (Anggota $record) {
                    return $record->tanggal_lahir
                        ? Carbon::parse($record->tanggal_lahir)->age . ' tahun'
                        : '-';
                }),

            ExportColumn::make('status_jemaat')
                ->label('Status'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Ekspor anggota berhasil diselesaikan dan ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' baris berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' baris gagal diekspor.';
        }

        return $body;
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        $query->with(['region', 'cluster', 'organization', 'pasangan', 'anaks']);

        if (!auth()->user()?->is_super_admin) {
            $query->where('organization_id', auth()->user()->organization_id);
        }

        return $query;
    }
}
