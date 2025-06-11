<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MinatResource\Pages;
use App\Filament\Resources\MinatResource\RelationManagers;
use App\Models\Minat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MinatResource extends Resource
{
    protected static ?string $model = Minat::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
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
            'index' => Pages\ListMinats::route('/'),
            'create' => Pages\CreateMinat::route('/create'),
            'edit' => Pages\EditMinat::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Master Data');
    }

    public static function getNavigationLabel(): string {
        return 'Minat';
    }

    public static function getPluralLabel(): string {
        return 'Minat';
    }

    public static function getModelLabel(): string {
        return 'Minat';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->is_super_admin || auth()->user()?->can('view_any_minat');
    }
}
