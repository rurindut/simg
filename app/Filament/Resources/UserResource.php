<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Select;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\ForceDeleteAction;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(255)
                    ->unique(
                        table: 'users',
                        column: 'email',
                        ignoreRecord: true
                    ),

                Forms\Components\TextInput::make('password')
                    ->same('passwordConfirmation')
                    ->password()
                    ->revealable()
                    ->maxLength(255)
                    ->required(fn ($component, $get, $livewire, $model, $record, $set, $state) => $record === null)
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state)),

                Forms\Components\TextInput::make('passwordConfirmation')
                    ->password()
                    ->revealable()
                    ->dehydrated(false)
                    ->maxLength(255),

                Forms\Components\Select::make('organization_id')
                    ->label('Organisasi')
                    ->relationship('organization', 'name')
                    ->searchable()
                    ->visible(fn () => auth()->user()?->is_super_admin ?? false)
                    ->required(fn () => auth()->user()?->is_super_admin ?? false),

                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->options(function () {
                        $query = Role::query();
                        if (!auth()->user()?->hasRole('super_admin')) {
                            $query->where('name', '!=', 'super_admin');
                        }

                        return $query->pluck('name', 'id');
                    })
                    ->preload()
                    ->searchable()
                    ->required(),

                Forms\Components\Textarea::make('alamat')
                    ->label('Alamat')
                    ->maxLength(255)
                    ->nullable(),
                
                Forms\Components\TextInput::make('no_telp')
                    ->label('No. Telepon')
                    ->maxLength(20)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_telp')
                    ->label('No. Telepon')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime(),
                
                Tables\Columns\TextColumn::make('organization.name')
                    ->label('Gereja')
                    ->visible(fn () => auth()->user()?->hasRole('super_admin'))
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);

        $user = auth()->user();

        if (!$user->hasRole('super_admin')) {
            $query->where('organization_id', $user->organization_id);
        }

        return $query;
    }

    public static function getNavigationGroup(): ?string
    {
        return Utils::isResourceNavigationGroupEnabled()
            ? __('Pengaturan')
            : '';
    }

}
