<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UltahPernikahanResource\Pages;
use App\Filament\Resources\UltahPernikahanResource\RelationManagers;
use App\Models\Anggota;
use App\Models\Pasangan;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class UltahPernikahanResource extends Resource
{
    protected static ?string $model = Anggota::class;
    protected static ?string $slug = 'ultah-pernikahan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(static::getEloquentQuery())
            ->columns([
                TextColumn::make('nama_lengkap')->label('Nama'),
                // Tables\Columns\TextColumn::make('tanggal_lahir')
                // ->label('Tanggal Lahir')
                // ->date('d M Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()
                    ->url(fn (Anggota $record) => route('filament.admin.resources.anggota.edit', ['record' => $record]))
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        // $query = parent::getEloquentQuery();

        $start = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $end = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $subquery = Pasangan::query()
            ->whereNotNull('tanggal_pemberkatan')
            ->whereRaw('DATE_FORMAT(tanggal_pemberkatan, "%m-%d") BETWEEN ? AND ?', [$start, $end])
            ->get();
            
        // $query->whereHas('pasangan', function ($q) use ($start, $end) {
        //     $q->whereNotNull('tanggal_pemberkatan')
        //       ->whereRaw('DATE_FORMAT(tanggal_pemberkatan, "%m-%d") BETWEEN ? AND ?', [
        //           $start->format('m-d'),
        //           $end->format('m-d'),
        //       ]);
        // });

        // $query->where(function ($q) {
        //     $q->whereNull('pasangan_nia')
        //       ->orWhereRaw('nia < pasangan_nia');
        // });

        $dataNia = [];

        foreach ($subquery as $pasangan) {
            $anggota1 = Anggota::where('nia', $pasangan->nia)->first();
            $anggota2 = Anggota::find($pasangan->anggota_id);
    
            // Skip jika keduanya tidak ditemukan
            if (!$anggota1 && !$anggota2) {
                continue;
            }

            if (
                ($anggota1 && $anggota1->status_hidup !== 'hidup') &&
                ($anggota2 && $anggota2->status_hidup !== 'hidup')
            ) {
                continue;
            }

            if ($anggota1 && $anggota2) {
                $dataNia[] = $anggota1->nia < $anggota2->nia ? $anggota1->nia : $anggota2->nia;
            } elseif ($anggota1) {
                $dataNia[] = $anggota1->nia;
            } elseif ($anggota2) {
                $dataNia[] = $anggota2->nia;
            } else {
                continue;
            }
        }

        $query = Anggota::query()
            ->whereIn('nia', $dataNia);

        if (!auth()->user()?->is_super_admin) {
            $query->where('organization_id', auth()->user()->organization_id);
        }
    
        return $query;
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
            'index' => Pages\ListUltahPernikahans::route('/'),
            // 'create' => Pages\CreateUltahPernikahan::route('/create'),
            // 'edit' => Pages\EditUltahPernikahan::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Keanggotaan');
    }

    public static function getNavigationLabel(): string {
        return 'Ultah Pernikahan';
    }

    public static function getPluralLabel(): string {
        return 'Ultah Pernikahan';
    }

    public static function getModelLabel(): string {
        return 'Anggota';
    }

    public static function getNavigationSort(): ?int
    {
        return 5;
    }
}
