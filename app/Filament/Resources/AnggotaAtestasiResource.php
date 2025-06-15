<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnggotaAtestasiResource\Pages;
use App\Filament\Resources\AnggotaAtestasiResource\RelationManagers;
use App\Models\Anggota;
use App\Models\Atestasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnggotaAtestasiResource extends Resource
{
    protected static ?string $model = \App\Models\Atestasi::class;

    protected static ?string $slug = 'anggota-atestasi';

    public static function table(Table $table): Table
    {
        return $table
            ->query(static::getEloquentQuery())
            ->columns([
                TextColumn::make('anggota.nama')->label('Nama Anggota'),
                TextColumn::make('tipe')->label('Tipe'),
                TextColumn::make('tanggal')->label('Tanggal')->date(),
                TextColumn::make('asal_gereja')->label('Asal'),
                TextColumn::make('tujuan_gereja')->label('Tujuan'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // EditAction::make()
                //     ->url(fn (Anggota $record) => route('filament.admin.resources.anggota.edit', ['record' => $record]))
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
        ->whereHas('anggota', function ($query) {
            if (! auth()->user()?->is_super_admin) {
                $query->where('organization_id', auth()->user()->organization_id);
            }
        });
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
            'index' => Pages\ListAnggotaAtestasis::route('/'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Keanggotaan');
    }

    public static function getNavigationLabel(): string {
        return 'Anggota Atestasi';
    }

    public static function getPluralLabel(): string {
        return 'Anggota Atestasi';
    }

    public static function getModelLabel(): string {
        return 'Anggota';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }
}
