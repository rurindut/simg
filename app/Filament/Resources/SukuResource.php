<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SukuResource\Pages;
use App\Filament\Resources\SukuResource\RelationManagers;
use App\Models\Suku;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SukuResource extends Resource
{
    protected static ?string $model = Suku::class;

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
            'index' => Pages\ListSukus::route('/'),
            'create' => Pages\CreateSuku::route('/create'),
            'edit' => Pages\EditSuku::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Master Data');
    }

    public static function getNavigationLabel(): string {
        return 'Suku';
    }

    public static function getPluralLabel(): string {
        return 'Suku';
    }

    public static function getModelLabel(): string {
        return 'Suku';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->is_super_admin || auth()->user()?->can('view_any_suku');
    }
}
