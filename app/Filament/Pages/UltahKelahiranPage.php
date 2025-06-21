<?php

namespace App\Filament\Pages;

use App\Models\Anggota;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class UltahKelahiranPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-gift';
    protected static ?string $navigationLabel = 'Ultah Kelahiran';
    protected static ?string $title = 'Ultah Kelahiran';
    protected static ?string $slug = 'ultah_kelahiran';
    protected static ?string $navigationGroup = 'Keanggotaan';
    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.ultah-kelahiran-page';

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
                TextColumn::make('tanggal_lahir')->label('Tanggal Lahir')->date('d M Y'),
                TextColumn::make('panggilan')->label('Panggilan'),
                TextColumn::make('nomor_hp')->label('No. HP')->sortable(),
                TextColumn::make('keluarga')
                    ->label('Keluarga')
                    ->getStateUsing(function ($record) {
                        $pasangan = optional($record->pasangan)?->nama;
                        $jumlahAnak = $record->anaks()->count();
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
                TextColumn::make('usia')
                    ->label('Usia')
                    ->getStateUsing(function ($record) {
                        return $record->tanggal_lahir
                            ? \Carbon\Carbon::parse($record->tanggal_lahir)->age . ' tahun'
                            : '-';
                    }),
            ]);
    }

    protected function getQuery(): Builder
    {
        $start = Carbon::now()->startOfWeek(Carbon::MONDAY)->format('m-d');
        $end = Carbon::now()->endOfWeek(Carbon::SUNDAY)->format('m-d');

        return Anggota::query()
            ->when(!auth()->user()?->is_super_admin, fn($query) =>
                $query->where('organization_id', auth()->user()->organization_id)
            )
            ->where('status_hidup', 'hidup')
            ->whereRaw('DATE_FORMAT(tanggal_lahir, "%m-%d") BETWEEN ? AND ?', [$start, $end]);
    }
}
