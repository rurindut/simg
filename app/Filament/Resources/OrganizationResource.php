<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationResource\Pages;
use App\Filament\Resources\OrganizationResource\RelationManagers;
use App\Models\Organization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?int $navigationSort = 1;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'update',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('logo')
                    ->label('Logo')
                    ->image()
                    ->imagePreviewHeight('150')
                    ->directory('organization-logos')
                    ->visibility('public')
                    ->enableOpen()
                    ->enableDownload()
                    ->columnSpan(1),

                TextInput::make('name')
                    ->label('Nama Gereja')
                    ->required(),

                TextInput::make('initial')
                    ->label('Inisial')
                    ->required(),

                TextInput::make('institution')
                    ->label('Lembaga')
                    ->required(),

                TextInput::make('branch')
                    ->label('Cabang')
                    ->required(),

                Textarea::make('address')
                    ->label('Alamat')
                    ->rows(2),

                TextInput::make('email')
                    ->label('Email')
                    ->email(),

                TextInput::make('phone')
                    ->label('Telepon'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
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
            'index' => Pages\ListOrganizations::route('/'),
            'create' => Pages\CreateOrganization::route('/create'),
            'edit' => Pages\EditOrganization::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string {
        return 'Gereja';
    }

    public static function getPluralLabel(): string {
        return 'Gereja';
    }

    public static function getModelLabel(): string {
        return 'Gereja';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('super_admin') || auth()->user()?->organization_id !== null;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (!auth()->user()->is_super_admin) {
            $query->where('id', auth()->user()->organization_id);
        }

        return $query;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Pengaturan');
    }

    public static function canEdit(Model $record): bool
    {
        if (auth()->user()->hasRole('super_admin')) return true;

        return $record->id === auth()->user()->organization_id;
    }
}
