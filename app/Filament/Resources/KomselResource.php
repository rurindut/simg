<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KomselResource\Pages;
use App\Filament\Resources\KomselResource\RelationManagers;
use App\Models\Komsel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KomselResource extends Resource
{
    protected static ?string $model = Komsel::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('organization_id')
                    ->label('Organisasi')
                    ->relationship('organization', 'name')
                    ->preload()
                    ->searchable()
                    ->required()
                    ->visible(fn () => auth()->user()?->is_super_admin),
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
            'index' => Pages\ListKomsels::route('/'),
            'create' => Pages\CreateKomsel::route('/create'),
            'edit' => Pages\EditKomsel::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Data Pastoral');
    }

    public static function getNavigationLabel(): string {
        return 'Komsel';
    }

    public static function getPluralLabel(): string {
        return 'Komsel';
    }

    public static function getModelLabel(): string {
        return 'Komsel';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->is_super_admin || auth()->user()?->can('view_any_daftar::komsel');
    }
}
