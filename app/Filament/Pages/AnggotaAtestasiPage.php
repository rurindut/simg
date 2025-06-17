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
                TextColumn::make('anggota.nama')->label('Nama Anggota')->sortable(),
                TextColumn::make('tipe')->label('Tipe')->sortable(),
                TextColumn::make('tanggal')->label('Tanggal')->date()->sortable(),
                TextColumn::make('asal_gereja')->label('Asal'),
                TextColumn::make('tujuan_gereja')->label('Tujuan'),
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
