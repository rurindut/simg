<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UltahKelahiranResource\Pages;
use App\Filament\Resources\UltahKelahiranResource\RelationManagers;
use App\Models\Anggota;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UltahKelahiranResource extends Resource
{
    protected static ?string $model = Anggota::class;
    protected static ?string $slug = 'ultah-kelahiran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(static::getEloquentQuery())
            ->columns([
                TextColumn::make('nama')->label('Nama'),
                TextColumn::make('tanggal_lahir')
                ->label('Tanggal Lahir')
                ->date('d M Y'),
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
        
        $query->where('status_hidup', 'hidup');

        $start = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $end = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $query->whereRaw('DATE_FORMAT(tanggal_lahir, "%m-%d") BETWEEN ? AND ?', [
            $start->format('m-d'),
            $end->format('m-d'),
        ]);
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
            'index' => Pages\ListUltahKelahirans::route('/'),
            // 'create' => Pages\CreateUltahKelahiran::route('/create'),
            // 'edit' => Pages\EditUltahKelahiran::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Keanggotaan');
    }

    public static function getNavigationLabel(): string {
        return 'Ultah Kelahiran';
    }

    public static function getPluralLabel(): string {
        return 'Ultah Kelahiran';
    }

    public static function getModelLabel(): string {
        return 'Anggota';
    }

    public static function getNavigationSort(): ?int
    {
        return 4;
    }
}
