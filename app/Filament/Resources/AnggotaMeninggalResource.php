<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnggotaMeninggalResource\Pages;
use App\Filament\Resources\AnggotaMeninggalResource\RelationManagers;
use App\Models\Anggota;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnggotaMeninggalResource extends Resource
{
    protected static ?string $model = Anggota::class;
    protected static ?string $slug = 'anggota-meninggal';

    public static function table(Table $table): Table
    {
        return $table
            ->query(static::getEloquentQuery())
            ->columns([
                TextColumn::make('nama_lengkap')->label('Nama'),
                // TextColumn::make('tanggal_meninggal')->label('Tanggal Meninggal'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()
                    ->url(fn (Anggota $record) => route('filament.admin.resources.anggota.edit', ['record' => $record]))
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (!auth()->user()?->is_super_admin) {
            $query->where('organization_id', auth()->user()->organization_id);
        }
        
        $query->where('status_hidup', 'meninggal');
        return $query;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnggotaMeninggals::route('/'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Keanggotaan');
    }

    public static function getNavigationLabel(): string {
        return 'Anggota Meninggal';
    }

    public static function getPluralLabel(): string {
        return 'Anggota Meninggal';
    }

    public static function getModelLabel(): string {
        return 'Anggota';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }
}
