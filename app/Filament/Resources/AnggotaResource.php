<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnggotaResource\Pages;
use App\Filament\Resources\AnggotaResource\RelationManagers;
use App\Models\Anggota;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;

class AnggotaResource extends Resource
{
    protected static ?string $model = Anggota::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $slug = 'anggota';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::dataPribadiForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nia')
                    ->label('NIA')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nomor_hp')
                    ->label('No. HP')
                    ->searchable(),

                TextColumn::make('region.name')
                    ->label('Wilayah')
                    ->searchable(),

                TextColumn::make('cluster.name')
                    ->label('Kelompok')
                    ->searchable(),

                TextColumn::make('keluarga')
                    ->label('Keluarga')
                    ->getStateUsing(function ($record) {
                        $pasangan = optional($record->pasangan)?->nama;
                        $jumlahAnak = $record->anaks()->count();
                        return ($pasangan ? 1 : 0) + $jumlahAnak;
                    }),

                TextColumn::make('usia')
                    ->label('Usia')
                    ->getStateUsing(function ($record) {
                        return $record->tanggal_lahir
                            ? \Carbon\Carbon::parse($record->tanggal_lahir)->age . ' tahun'
                            : '-';
                    }),

                TextColumn::make('status_jemaat')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'anggota' => 'success',
                        'simpatisan' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('organization.name')
                    ->label('Organisasi')
                    ->visible(fn () => auth()->user()?->is_super_admin),
            ])
            ->defaultSort('nama')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Detail'),
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
            // BaptisRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnggotas::route('/'),
            'create' => Pages\CreateAnggota::route('/create'),
            // 'edit' => Pages\EditAnggota::route('/{record}/edit'),
            'edit' => Pages\EditAnggota::route('/{record}/edit/data-pribadi'),
            'edit-baptis' => Pages\EditAnggotaBaptis::route('/{record}/edit/data-baptis'),
            'edit-atestasi' => Pages\EditAnggotaAtestasi::route('/{record}/edit/data-atestasi'),
            'edit-keluarga' => Pages\EditAnggotaKeluarga::route('/{record}/edit/data-keluarga'),
            'edit-aktivitas' => Pages\EditAnggotaAktivitas::route('/{record}/edit/data-aktivitas'),
            'view' => Pages\ViewAnggota::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (!auth()->user()?->is_super_admin) {
            $query->where('organization_id', auth()->user()->organization_id);
        }

        return $query;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Keanggotaan');
    }

    public static function getNavigationLabel(): string {
        return 'Anggota';
    }

    public static function getPluralLabel(): string {
        return 'Anggota';
    }

    public static function getModelLabel(): string {
        return 'Anggota';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->is_super_admin || auth()->user()?->can('view_any_anggota');
    }

    public static function dataPribadiForm(): array
    {
        $user = auth()->user();
        $isSuperAdmin = auth()->user()?->is_super_admin;
        // Log::debug($user);

        return [
        Section::make()
            ->schema([
            Select::make('organization_id')
                ->label('Organisasi')
                ->relationship('organization', 'name')
                ->required()
                ->columnSpanFull()
                ->visible(fn () => auth()->user()?->is_super_admin)
                ->default(fn () => auth()->user()?->organization_id)
                ->live()
                ->reactive()
                ->afterStateUpdated(function (callable $set) {
                    $set('region_id', null);
                    $set('cluster_id', null);
                }),
            Hidden::make('organization_id')
                ->columnSpanFull()
                ->default(fn () => auth()->user()?->organization_id)
                ->visible(fn () => !auth()->user()?->is_super_admin),
            TextInput::make('nia')->required()->columnSpan(2),
            TextInput::make('nik')->columnSpan(2),
            Select::make('sapaan')->options([
                'bapak' => 'Bapak',
                'ibu' => 'Ibu',
                'saudara' => 'Saudara',
                'saudari' => 'Saudari',
            ])->columnSpan(1),
            TextInput::make('nama')->required()->columnSpan(2),
            TextInput::make('panggilan')->columnSpan(1),
            Select::make('jenis_kelamin')->options([
                'laki-laki' => 'Laki-laki',
                'perempuan' => 'Perempuan',
            ])->columnSpan(1),
            TextInput::make('tempat_lahir')->columnSpan(2),
            DatePicker::make('tanggal_lahir')->columnSpan(1),
            Select::make('status_perkawinan')->options([
                'kawin' => 'Kawin',
                'belum_kawin' => 'Belum Kawin',
                'cerai_hidup' => 'Cerai Hidup',
                'cerai_mati' => 'Cerai Mati',
            ])->columnSpan(1),
            Select::make('suku_id')->searchable()->relationship('suku', 'name')->preload()->columnSpan(1),
            Select::make('golongan_darah')->options([
                '-' => '-',
                'A' => 'A',
                'A+' => 'A+',
                'A-' => 'A-',
                'B' => 'B',
                'B+' => 'B+',
                'B-' => 'B-',
                'AB' => 'AB',
                'AB+' => 'AB+',
                'AB-' => 'AB-',
                'O' => 'O',
                'O+' => 'O+',
                'O-' => 'O-',
            ])->columnSpan(1),
            Toggle::make('donor')->label('Donor Darah')->columnSpan(1),
        ])->columns(4),
        Section::make()
            ->schema([
            TextInput::make('email')->email()->columnSpan(2),
            TextInput::make('nomor_hp')->columnSpan(1),
            TextInput::make('telepon')->columnSpan(1),
            DatePicker::make('tanggal_registrasi')->columnSpan(2),
            TextInput::make('maps')->columnSpan(2),
            Textarea::make('alamat_ktp')->columnSpan(2),
            TextInput::make('kecamatan_ktp')->columnSpan(2),
            Textarea::make('alamat_domisili')->columnSpan(2),
            TextInput::make('kecamatan_domisili')->columnSpan(2),
            Select::make('status_tinggal')->options([
                'tinggal' => 'Tinggal',
                'luar_kota' => 'Luar Kota',
            ])->columnSpan(2),
            Select::make('status_hidup')->options([
                'hidup' => 'Hidup',
                'meninggal' => 'Meninggal',
            ])->columnSpan(2),
        ])->columns(4),
        Section::make()
            ->schema([
            Select::make('pendidikan_id')->relationship('pendidikan', 'name')->searchable()->columnSpan(1),
            TextInput::make('disiplin_ilmu')->columnSpan(1),
            TextInput::make('jurusan')->columnSpan(1),
            TextInput::make('gelar')->columnSpan(1),
            Select::make('hobi_id')->relationship('hobi', 'name')->searchable()->columnSpan(1),
            Select::make('minat_id')->relationship('minat', 'name')->searchable()->columnSpan(1),
            Select::make('status_jemaat')->options([
                'anggota' => 'Anggota',
                'simpatisan' => 'Simpatisan',
            ])->columnSpan(1),
        ])->columns(4),
        Section::make()
            ->schema([
            Select::make('region_id')
            ->label('Wilayah')
            ->relationship('region', 'name')
            ->options(function (callable $get) {
                $organizationId = $get('organization_id') ?? auth()->user()->organization_id;
                if (!$organizationId) return [];

                return \App\Models\Region::where('organization_id', $organizationId)
                    ->pluck('name', 'id')
                    ->toArray();
            })
            ->searchable()
            ->preload()
            ->reactive()
            ->live(),
            Select::make('cluster_id')
            ->label('Kelompok')
            ->relationship('cluster', 'name')
            ->searchable()
            ->preload()
            ->disabled(fn (callable $get) => !$get('region_id'))
            ->options(function (callable $get) {
                $regionId = $get('region_id');
                if (!$regionId) return [];

                return \App\Models\Cluster::where('region_id', $regionId)
                    ->pluck('name', 'id')
                    ->toArray();
            })
            ->reactive(),
        ])->columns(2),
        Section::make()
            ->schema([
            FileUpload::make('foto_anda')->image()->directory('anggota'),
            FileUpload::make('foto_keluarga')->image()->directory('anggota-keluarga'),
        ])->columns(2)
        ];
    }

    public static function dataBaptisForm(): array
    {
        return [
            Section::make('Baptis Anak')
                ->schema([
                    Hidden::make('jenis')->default('anak'),
                    Select::make('tempat_baptis')
                    ->label('Tempat Baptis (Gereja)')
                    ->options(function () {
                        $query = \App\Models\Organization::query();

                        if (! auth()->user()?->is_super_admin) {
                            $query->where('id', auth()->user()->organization_id);
                        }

                        return $query->pluck('name', 'id')->prepend('Gereja Lain', 0);
                    }),
                    TextInput::make('no_piagam'),
                    DatePicker::make('tanggal'),
                    TextInput::make('pendeta'),
                    TextInput::make('gereja'),
                    Textarea::make('alamat')->rows(2),
                    FileUpload::make('lampiran')->image(),
                ])
                ->columns(2)
                ->statePath('baptisAnak'),
            Section::make('Baptis Sidi')
                ->schema([
                    Hidden::make('jenis')->default('sidi'),
                    Select::make('tempat_baptis')
                    ->label('Tempat Baptis (Gereja)')
                    ->options(function () {
                        $query = \App\Models\Organization::query();

                        if (! auth()->user()?->is_super_admin) {
                            $query->where('id', auth()->user()->organization_id);
                        }

                        return $query->pluck('name', 'id')->prepend('Gereja Lain', 0);
                    }),
                    TextInput::make('no_piagam'),
                    DatePicker::make('tanggal'),
                    TextInput::make('pendeta'),
                    TextInput::make('gereja'),
                    Textarea::make('alamat')->rows(2),
                    FileUpload::make('lampiran')->image(),
                ])
                ->columns(2)
                ->statePath('baptisSidi'),
        ];
    }

    public static function dataAtestasiForm(): array
    {
        return [
            Select::make('tipe')
                ->options([
                    'masuk' => 'Masuk',
                    'keluar' => 'Keluar',
                ])
                ->required(),

            DatePicker::make('tanggal')
                ->label('Tanggal Atestasi')
                ->required(),

            TextInput::make('gereja_dari')
                ->label('Gereja Dari')
                ->required(),

            Textarea::make('alamat_asal')
                ->label('Alamat Dari')
                ->rows(2)
                ->required(),

            TextInput::make('gereja_tujuan')
                ->label('Gereja Tujuan')
                ->required(),

            Textarea::make('alamat_tujuan')
                ->label('Alamat Tujuan')
                ->required()
                ->rows(2),

            TextInput::make('nomor_surat')
                ->label('Nomor Surat'),

            Textarea::make('alasan')
                ->label('Alasan')
                ->rows(3),
        ];
    }

    public static function dataKeluargaForm(): array
    {
        return [
            Section::make('Data Orang Tua')
                ->schema([
                    Hidden::make('ayah.id'),
                    Hidden::make('ayah.hubungan')->default('ayah'),
                    TextInput::make('ayah.nia')
                        ->label('NIA Ayah'),
                    TextInput::make('ayah.nama')
                        ->label('Nama Ayah'),
                
                    Hidden::make('ibu.id'),
                    Hidden::make('ibu.hubungan')->default('ibu'),
                    TextInput::make('ibu.nia')
                        ->label('NIA Ibu'),
                    TextInput::make('ibu.nama')
                        ->label('Nama Ibu'),
                ])
                ->columns(2),

            Section::make('Data Pasangan')
                ->schema([
                    TextInput::make('pasangan.nia')
                        ->label('NIA Pasangan'),
                    TextInput::make('pasangan.nama')
                        ->label('Nama Pasangan'),
                    TextInput::make('pasangan.no_akta_nikah')
                        ->label('No. Akta Nikah'),
                    DatePicker::make('pasangan.tanggal_catatan_sipil')
                        ->label('Tanggal Catatan Sipil'),
                    TextInput::make('pasangan.tempat_catatan_sipil')
                        ->label('Tempat Catatan Sipil'),
                    TextInput::make('pasangan.no_piagam')
                        ->label('No. Piagam'),
                    DatePicker::make('pasangan.tanggal_pemberkatan')
                        ->label('Tanggal Pemberkatan'),
                    TextInput::make('pasangan.pendeta')
                        ->label('Pendeta'),
                    TextInput::make('pasangan.gereja')
                        ->label('Gereja'),
                    Textarea::make('pasangan.alamat_gereja')
                        ->label('Alamat Gereja'),
                    FileUpload::make('pasangan.akta_catatan_sipil')
                        ->label('Akta Catatan Sipil')
                        ->image()
                        ->directory('akta-catatan-sipil'),
                    FileUpload::make('pasangan.piagam_pemberkatan')
                        ->label('Piagam Pemberkatan')
                        ->image()
                        ->directory('akta-catatan-sipil'),
                ])
                ->columns(2),

            Section::make('anaks')
                ->label('Data Anak')
                ->schema([
                    Repeater::make('anaks')
                        ->label('Anak')
                        ->relationship('anaks')
                        ->schema([
                            TextInput::make('nia')
                                ->label('NIA')
                                ->columnSpan(1),
                            TextInput::make('nama')
                                ->label('Nama')->required()
                                ->columnSpan(1),
                            TextInput::make('tempat_lahir')
                                ->label('Tempat Lahir')
                                ->columnSpan(1),
                            DatePicker::make('tanggal_lahir')
                                ->label('Tanggal Lahir')
                                ->columnSpan(1),
                            Select::make('jenis_kelamin')
                                ->options([
                                    'Laki-laki' => 'Laki-laki',
                                    'Perempuan' => 'Perempuan',
                                ])->required()
                                ->columnSpan(1),
                            TextInput::make('jemaat')
                                ->label('Jemaat')
                                ->columnSpan(1),
                            Textarea::make('alamat')
                                ->label('Alamat')
                                ->columnSpan(2),
                            // Textarea::make('keterangan')
                            //     ->label('Keterangan')
                            //     ->columnSpan(2),
                        ])
                        ->defaultItems(0)
                        ->reorderable()
                        ->addActionLabel('Tambah Anak')
                        ->columns(4),
                ])
                ->collapsible(),            
        ];
    }

    public static function dataActivitasForm(): array
    {
        return [
        Section::make('Pengalaman Aktivitas Gerejawi')
            ->schema([
                Repeater::make('pengalamanGerejawis')
                    ->label('')
                    ->relationship('pengalamanGerejawis')
                    ->defaultItems(0)
                    ->schema([
                        TextInput::make('bidang')->required()->label('Bidang Pelayanan / Lembaga / Komisi'),
                        TextInput::make('jabatan')->required()->label('Jabatan / Keterangan Tugas'),
                        TextInput::make('tahun_mulai')->numeric(),
                        TextInput::make('tahun_selesai')->numeric(),
                    ])
                    ->columns(2),
            ]),
        
        Section::make('Aktivitas Sosial')
            ->schema([
                Repeater::make('aktivitasSosials')
                    ->label('')
                    ->relationship('aktivitasSosials')
                    ->defaultItems(0)
                    ->schema([
                        TextInput::make('organisasi')->required(),
                        TextInput::make('kegiatan')->required(),
                        TextInput::make('jabatan')->required(),
                        TextInput::make('tahun_mulai')->numeric(),
                        TextInput::make('tahun_selesai')->numeric(),
                    ])
                    ->columns(2),
            ]),

        Section::make('Pekerjaan')
            ->schema([
                Repeater::make('pekerjaans')
                    ->label('')
                    ->relationship('pekerjaans')
                    ->defaultItems(0)
                    ->schema([
                        TextInput::make('profesi')->required(),
                        TextInput::make('kantor')->label('Nama Kantor')->required(),
                        Textarea::make('alamat')->nullable(),
                        TextInput::make('bagian')->nullable(),
                        TextInput::make('jabatan')->nullable(),
                        TextInput::make('tahun_mulai')->numeric(),
                        TextInput::make('tahun_selesai')->numeric(),
                    ])
                    ->columns(2),
            ])
        ];
    }
}
