<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalPetugasResource\Pages;
use App\Filament\Resources\JadwalPetugasResource\RelationManagers;
use App\Models\JadwalPetugas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Card;

class JadwalPetugasResource extends Resource
{
    protected static ?string $model = JadwalPetugas::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Data Pastoral';
    protected static ?string $navigationLabel = 'Jadwal Petugas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Select::make('jadwal_ibadah_id')
                        ->relationship('jadwalIbadah', 'nama')
                        ->label('Jadwal Ibadah')
                        ->required(),
        
                    DatePicker::make('tanggal')
                        ->label('Tanggal')
                        ->required(),
        
                    TimePicker::make('jam_mulai')
                        ->label('Jam Mulai')
                        ->required(),
        
                    TimePicker::make('jam_selesai')
                        ->label('Jam Selesai')
                        ->nullable(),

                    Select::make('daftar_pelayanan_id')
                        ->label('Jenis Pelayanan')
                        ->relationship('pelayanan', 'nama')
                        ->required(),

                    Select::make('anggota_id')
                        ->label('Nama Anggota')
                        ->relationship('anggota', 'nama')
                        ->searchable()
                        ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jadwalIbadah.nama')->label('Jadwal Ibadah')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('tanggal')->date('d M Y')->label('Tanggal')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('jam_mulai')->time('H:i')->label('Mulai'),
                Tables\Columns\TextColumn::make('jam_selesai')->time('H:i')->label('Selesai'),
                Tables\Columns\TextColumn::make('daftarPelayanan.nama')->label('Pelayanan')->sortable(),
                Tables\Columns\TextColumn::make('anggota.nama')->label('Anggota')->sortable(),
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
            'index' => Pages\ListJadwalPetugas::route('/'),
            'create' => Pages\CreateJadwalPetugas::route('/create'),
            'edit' => Pages\EditJadwalPetugas::route('/{record}/edit'),
        ];
    }
}
