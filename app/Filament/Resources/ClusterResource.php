<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClusterResource\Pages;
use App\Filament\Resources\ClusterResource\RelationManagers;
use App\Models\Cluster;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rule;

class ClusterResource extends Resource
{
    protected static ?string $model = Cluster::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $user = auth()->user();
        $isSuperAdmin = auth()->user()?->is_super_admin;

        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                // Hanya tampil jika super admin
                Forms\Components\Select::make('organization_id')
                    ->label('Organisasi')
                    ->relationship('organization', 'name')
                    ->options(\App\Models\Organization::pluck('name', 'id'))
                    ->required($isSuperAdmin)
                    ->visible($isSuperAdmin)
                    ->dehydrated(false)
                    ->live(),

                Forms\Components\Select::make('region_id')
                    ->label('Wilayah')
                    ->options(function (callable $get) use ($user, $isSuperAdmin) {
                        $organizationId = $isSuperAdmin
                            ? $get('organization_id')
                            : $user->organization_id;

                        if (!$organizationId) return [];

                        return \App\Models\Region::where('organization_id', $organizationId)
                            ->pluck('name', 'id');
                    })
                    ->relationship('region', 'name')
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->disabled(fn (callable $get) => !$get('organization_id') && $isSuperAdmin)
                    ->hint($isSuperAdmin ? 'Pilih organisasi terlebih dahulu' : null)
                    ->rules([
                        function (callable $get) use ($user, $isSuperAdmin) {
                            $organizationId = $isSuperAdmin
                                ? $get('organization_id')
                                : $user->organization_id;

                            return [
                                Rule::exists('regions', 'id')
                                    ->where('organization_id', $organizationId),
                            ];
                        },
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        $isSuperAdmin = auth()->user()?->is_super_admin;
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('region.name')
                    ->label('Wilayah')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('organization.name')
                    ->label('Organisasi')
                    ->sortable()
                    ->visible($isSuperAdmin)
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
            'index' => Pages\ListClusters::route('/'),
            'create' => Pages\CreateCluster::route('/create'),
            'edit' => Pages\EditCluster::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Pengaturan');
    }

    public static function getNavigationLabel(): string {
        return 'Kelompok';
    }

    public static function getPluralLabel(): string {
        return 'Kelompok';
    }

    public static function getModelLabel(): string {
        return 'Kelompok';
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        if ($user?->is_super_admin) {
            return parent::getEloquentQuery()->with(['region']);
        }

        return parent::getEloquentQuery()
            ->whereHas('region', function ($query) use ($user) {
                $query->where('organization_id', $user->organization_id);
            })
            ->with(['region']);
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->is_super_admin || auth()->user()?->can('view_any_cluster');
    }
}
