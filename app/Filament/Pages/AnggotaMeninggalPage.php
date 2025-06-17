<?php

namespace App\Filament\Pages;

use App\Models\Anggota;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

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
                TextColumn::make('nama')->label('Nama'),
                TextColumn::make('nia')->label('NIA'),                
                TextColumn::make('status_hidup')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => $state === 'hidup' ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
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
