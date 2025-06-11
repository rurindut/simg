<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HobiResource\Pages;
use App\Filament\Resources\HobiResource\RelationManagers;
use App\Models\Hobi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HobiResource extends Resource
{
    protected static ?string $model = Hobi::class;

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
            'index' => Pages\ListHobis::route('/'),
            'create' => Pages\CreateHobi::route('/create'),
            'edit' => Pages\EditHobi::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Master Data');
    }

    public static function getNavigationLabel(): string {
        return 'Hobi';
    }

    public static function getPluralLabel(): string {
        return 'Hobi';
    }

    public static function getModelLabel(): string {
        return 'Hobi';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->is_super_admin || auth()->user()?->can('view_any_hobi');
    }
}
