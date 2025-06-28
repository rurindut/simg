<?php

namespace App\Filament\Pages;

use App\Models\Anggota;
use App\Models\Pernikahan;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class UltahPernikahanPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationLabel = 'Ultah Pernikahan';
    protected static ?string $title = 'Ultah Pernikahan';
    protected static ?string $slug = 'ultah_pernikahan';
    protected static ?string $navigationGroup = 'Keanggotaan';
    protected static ?int $navigationSort = 6;

    protected static string $view = 'filament.pages.ultah-pernikahan-page';

    public static function canAccess(): bool
    {
        return auth()->user()?->is_super_admin || auth()->user()?->can('view_anggota');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
            ->columns([
                TextColumn::make('nama')->label('Nama'),
                TextColumn::make('nama_pasangan')
                    ->label('Pasangan')
                    ->getStateUsing(fn ($record) => $record->nama_pasangan ?? '-'),
                TextColumn::make('pernikahan.tanggal_catatan_sipil')
                    ->label('Tanggal Catatan Sipil')
                    ->getStateUsing(function ($record) {
                        return $record->pernikahan_aktif?->tanggal_catatan_sipil;
                    })
                    ->date('d M Y'),
                TextColumn::make('ultah_ke')
                    ->label('Ultah Ke')
                    ->getStateUsing(function ($record) {
                        $tanggal = $record->pernikahan_aktif?->tanggal_catatan_sipil;

                        if (! $tanggal) {
                            return '-';
                        }

                        $tahun = \Carbon\Carbon::parse($tanggal)->diffInYears(now());

                        return $tahun > 0 ? round($tahun) . ' Tahun' : '-';
                    }),
                ]);
    }

    protected function getQuery(): Builder
    {
        $start = Carbon::now()->startOfWeek(Carbon::MONDAY)->format('m-d');
        $end = Carbon::now()->endOfWeek(Carbon::SUNDAY)->format('m-d');

        $isSuperAdmin = auth()->user()?->is_super_admin;
        $organizationId = auth()->user()?->organization_id;

        $pernikahans = Pernikahan::query()
            ->whereNotNull('tanggal_catatan_sipil')
            ->whereRaw('DATE_FORMAT(tanggal_catatan_sipil, "%m-%d") BETWEEN ? AND ?', [$start, $end])
            ->get();

        $niaUtama = [];

        foreach ($pernikahans as $p) {
            $suami = $p->nia_suami ? Anggota::where('nia', $p->nia_suami)->first() : null;
            $istri = $p->nia_istri ? Anggota::where('nia', $p->nia_istri)->first() : null;

            if (
                !$isSuperAdmin &&
                (!$suami || $suami->organization_id !== $organizationId) &&
                (!$istri || $istri->organization_id !== $organizationId)
            ) {
                continue;
            }

            if ($suami && $suami->status_hidup === 'hidup') {
                $niaUtama[] = $suami->nia;
            } elseif ($istri && $istri->status_hidup === 'hidup') {
                $niaUtama[] = $istri->nia;
            }
        }

        $query = Anggota::query()->whereIn('nia', $niaUtama);

        if (!auth()->user()?->is_super_admin) {
            $query->where('organization_id', auth()->user()->organization_id);
        }
        
        return $query;
    }
}
