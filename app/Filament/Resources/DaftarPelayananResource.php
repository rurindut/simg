<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DaftarPelayananResource\Pages;
use App\Filament\Resources\DaftarPelayananResource\RelationManagers;
use App\Models\DaftarPelayanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DaftarPelayananResource extends Resource
{
    protected static ?string $model = DaftarPelayanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('keterangan')
                    ->label('Keterangan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListDaftarPelayanans::route('/'),
            'create' => Pages\CreateDaftarPelayanan::route('/create'),
            'edit' => Pages\EditDaftarPelayanan::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Master Data');
    }

    public static function getNavigationLabel(): string {
        return 'Daftar Pelayanan';
    }

    public static function getPluralLabel(): string {
        return 'Daftar Pelayanan';
    }

    public static function getModelLabel(): string {
        return 'Daftar Pelayanan';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->is_super_admin || auth()->user()?->can('view_any_daftar::pelayanan');
    }
}
