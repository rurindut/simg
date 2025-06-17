<?php

namespace App\Filament\Pages;

use App\Models\Anggota;
use App\Models\Pasangan;
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
                TextColumn::make('nama')->label('Nama Anggota'),
                TextColumn::make('pasangan.nama')->label('Nama Pasangan'),
                TextColumn::make('pasangan.tanggal_pemberkatan')->label('Tanggal Pemberkatan')->date('d M Y'),
            ]);
    }

    protected function getQuery(): Builder
    {
        $start = Carbon::now()->startOfWeek(Carbon::MONDAY)->format('m-d');
        $end = Carbon::now()->endOfWeek(Carbon::SUNDAY)->format('m-d');

        $pasangans = Pasangan::query()
            ->whereNotNull('tanggal_pemberkatan')
            ->whereRaw('DATE_FORMAT(tanggal_pemberkatan, "%m-%d") BETWEEN ? AND ?', [$start, $end])
            ->get();

        $niaUtama = [];
        
        foreach ($pasangans as $pasangan) {
            $anggota1 = Anggota::where('nia', $pasangan->nia)->first();
            $anggota2 = Anggota::find($pasangan->anggota_id);

            if (!$anggota1 && !$anggota2) continue;

            if (
                ($anggota1 && $anggota1->status_hidup !== 'hidup') &&
                ($anggota2 && $anggota2->status_hidup !== 'hidup')
            ) {
                continue;
            }

            if ($anggota1 && $anggota2) {
                $niaUtama[] = $anggota1->nia < $anggota2->nia ? $anggota1->nia : $anggota2->nia;
            } elseif ($anggota1) {
                $niaUtama[] = $anggota1->nia;
            } elseif ($anggota2) {
                $niaUtama[] = $anggota2->nia;
            }
        }
        
        $query = Anggota::query()->whereIn('nia', $niaUtama);

        if (!auth()->user()?->is_super_admin) {
            $query->where('organization_id', auth()->user()->organization_id);
        }
        
        return $query;
    }
}
