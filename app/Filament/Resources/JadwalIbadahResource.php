<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalIbadahResource\Pages;
use App\Filament\Resources\JadwalIbadahResource\RelationManagers;
use App\Models\JadwalIbadah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JadwalIbadahResource extends Resource
{
    protected static ?string $model = JadwalIbadah::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('organization_id')
                    ->label('Organisasi')
                    ->relationship('organization', 'name')
                    ->required()
                    ->columnSpanFull()
                    ->visible(fn () => auth()->user()?->is_super_admin)
                    ->default(fn () => auth()->user()?->organization_id),
                Forms\Components\Hidden::make('organization_id')
                    ->columnSpanFull()
                    ->default(fn () => auth()->user()?->organization_id)
                    ->visible(fn () => !auth()->user()?->is_super_admin),
                Forms\Components\TextInput::make('nama')
                    ->label('Nama Ibadah')
                    ->required()
                    ->maxLength(100),
                // Forms\Components\DatePicker::make('tanggal')
                //     ->label('Tanggal')
                //     ->placeholder('Opsional'),
                Forms\Components\Select::make('hari')
                    ->label('Hari')
                    ->options([
                        'Senin' => 'Senin',
                        'Selasa' => 'Selasa',
                        'Rabu' => 'Rabu',
                        'Kamis' => 'Kamis',
                        'Jumat' => 'Jumat',
                        'Sabtu' => 'Sabtu',
                        'Minggu' => 'Minggu',
                    ])
                    ->placeholder('Pilih Hari')
                    ->searchable()
                    ->nullable(),
                Forms\Components\TimePicker::make('jam_mulai')
                    ->label('Jam Mulai')
                    ->required(),
                Forms\Components\TimePicker::make('jam_selesai')
                    ->label('Jam Selesai')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('organization.name')
                    ->label('Organisasi')
                    ->visible(fn () => auth()->user()?->is_super_admin)
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')->label('Nama Ibadah')->searchable()->sortable(),
                // Tables\Columns\TextColumn::make('tanggal')->label('Tanggal')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('hari')->label('Hari'),
                Tables\Columns\TextColumn::make('jam_mulai')->label('Jam Mulai')->time('H:i'),
                Tables\Columns\TextColumn::make('jam_selesai')->label('Jam Selesai')->time('H:i'),
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
            'index' => Pages\ListJadwalIbadahs::route('/'),
            'create' => Pages\CreateJadwalIbadah::route('/create'),
            'edit' => Pages\EditJadwalIbadah::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->is_super_admin || auth()->user()?->can('view_any_jadwal::ibadah');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Data Pastoral');
    }

    public static function getNavigationLabel(): string {
        return 'Jadwal Ibadah';
    }

    public static function getPluralLabel(): string {
        return 'Jadwal Ibadah';
    }

    public static function getModelLabel(): string {
        return 'Jadwal Ibadah';
    }

}
