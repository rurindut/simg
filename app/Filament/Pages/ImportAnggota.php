<?php

namespace App\Filament\Pages;

use App\Models\Anggota;
use App\Models\Atestasi;
use App\Models\Organization;
use App\Models\OrangTua;
use App\Models\Pekerjaan;
use App\Models\Pendidikan;
use App\Models\Pernikahan;
use App\Models\Region;
use App\Models\Suku;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\TemporaryUploadedFile;

class ImportAnggota extends Page
{
    use WithFileUploads;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';
    protected static ?string $title = 'Import Data Jemaat';
    protected static ?string $navigationGroup = 'Keanggotaan';
    protected static ?string $navigationLabel = 'Import Data Jemaat';
    protected static ?int $navigationSort = 7;
    protected static string $view = 'filament.pages.import-anggota';

    // public $organization_id;
    // public $status_jemaat;
    // public $file;
    public $data = [];

    public function mount(): void
    {
        $this->form->fill();

        // Default organization
        if (!auth()->user()->is_super_admin) {
            $this->organization_id = auth()->user()->organization_id;
        }
    }

    public function tryParseDate($value): ?string
    {
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function parseTanggalLahir($raw): ?string
    {
        $raw = trim($raw);

        if (empty($raw)) {
            return null;
        }

        try {
            $date = \DateTime::createFromFormat('d-M-y', $raw);

            if (! $date) {
                return null;
            }
    
            $year = (int) $date->format('Y');
            $nowYear = (int) now()->format('Y');
    
            if ($year > $nowYear) {
                $year -= 100;
                $date->setDate($year, (int)$date->format('m'), (int)$date->format('d'));
            }
    
            return $date->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }

        return null;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('organization_id')
                    ->label('Organisasi')
                    ->options(Organization::pluck('name', 'id'))
                    ->required()
                    ->visible(fn () => auth()->user()->is_super_admin)
                    ->default(fn () => auth()->user()?->organization_id),

                Forms\Components\Hidden::make('organization_id')
                    ->default(fn () => auth()->user()->organization_id)
                    ->visible(fn () => !auth()->user()->is_super_admin),

                Forms\Components\Select::make('status_jemaat')
                    ->label('Status Jemaat')
                    ->required()
                    ->options([
                        'anggota' => 'Anggota',
                        'simpatisan' => 'Simpatisan',
                    ]),

                Forms\Components\FileUpload::make('file')
                    ->label('Pilih File CSV')
                    ->acceptedFileTypes(['text/csv'])
                    ->required()
                    ->disk('local')
                    ->visibility('private')
                    ->multiple(false),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        try {
            // Log::debug($this->data);

            $fileArray = $this->data['file'];

            if (! is_array($fileArray)) {
                throw new \Exception('File upload tidak dikenali.');
            }

            $file = reset($fileArray);

            $statusJemaat = $this->data['status_jemaat'];

            $path = $file->storeAs('imports', $statusJemaat . '_' . now()->format('Ymd_His') . '.csv', 'local');
            $fullPath = storage_path('app/private/imports/' . basename($path));

            if (!file_exists($fullPath)) {
                throw new \Exception("File tidak ditemukan di path: $fullPath");
            }

            $rows = array_map('str_getcsv', file($fullPath));
            $header = array_map('trim', array_shift($rows));

            $dataPernikahan = [];
            $dataOrangtua = [];

            foreach ($rows as $row) {
                $data = array_combine($header, $row);

                $kecamatanKtp = $data['kota KTP'] . ($data['camat KTP'] ? ', '.$data['camat KTP'] : '') . ($data['lurah KTP'] ? ', '.$data['lurah KTP'] : '');
                $kecamatanDomisili = $data['Kota/Kab.'] . ($data['Kecamatan'] ? ', '.$data['Kecamatan'] : '') . ($data['Kelurahan'] ? ', '.$data['Kelurahan'] : '');

                $identifier = [];

                if ($statusJemaat === 'anggota') {
                    $identifier = ['nia' => $data['#GKI ADI'] ?? null];
                } else {
                    $identifier = ['nama' => $data['NAMA'] ?? null, 'nia' => NULL];
                }

                if(empty($identifier)) {
                    continue;
                }

                $anggota = Anggota::updateOrCreate($identifier, [
                    'nik'                   => $data['No KTP'] ?? null,
                    'sapaan'                => $data['sapaan'] ? strtolower($data['sapaan']): null,
                    'nama'                  => $data['NAMA'] ?? null,
                    'panggilan'             => $data['nick'] ?? null,
                    'jenis_kelamin'         => (strtolower($data['kelamin']) == 'pria') ? 'laki-laki' : ((strtolower($data['kelamin']) == 'wanita') ? 'perempuan' : 'laki-laki'),
                    'tempat_lahir'          => $data['kota_lhr'] ?? null,
                    'tanggal_lahir'         => ($data['thl_lhr'] && $data['thl_lhr'] != '') ? $this->parseTanggalLahir($data['thl_lhr']) : null,
                    'status_perkawinan'     => (strtolower($data['status_diri']) == 'cerai') ? 'cerai_hidup' : (in_array(strtolower($data['status_diri']), ['janda', 'duda']) ? 'cerai_mati' : (in_array(strtolower($data['status_diri']), ['belum menikah', 'tidak menikah']) ? 'belum_menikah' : (strtolower($data['status_diri']) ?? null))),
                    'suku_id'               => $data['etnis']
                                                ? Suku::firstOrCreate(
                                                    ['name' => trim($data['etnis'])],
                                                    ['name' => trim($data['etnis'])]
                                                )->id
                                                : null,
                    'golongan_darah'        => $data['darah'] ?? null,
                    'donor'                 => (strtolower($data['donor']) === true) ? 1 : 0 ?? 0,

                    'email'                 => $data['email 1'] ?? null,
                    'nomor_hp'              => $data['Telp / WA 1'] ?? null,
                    'telepon'               => $data['Telp / WA 2'] ?? null,
                    'tanggal_registrasi'    => ($data['Tgl Anggt'] && $data['Tgl Anggt'] != '') ? $this->tryParseDate($data['Tgl Anggt']) : null,
                    'alamat_ktp'            => $data['alamat KTP'] ?? null,
                    'kecamatan_ktp'         => $kecamatanKtp,
                    'alamat_domisili'       => $data['ALAMAT domisili'] ?? null,
                    'kecamatan_domisili'    => $kecamatanDomisili,

                    'status_tinggal'        => ($data['kota KTP'] && $data['kota KTP'] != $data['Kota/Kab.']) ? 'luar_kota' : 'tinggal',
                    'status_hidup'          => ($data['kubur_tgl']) ? 'meninggal' : 'hidup',
                    'pendidikan_id'         => $data['jenjang']
                                                ? Pendidikan::firstOrCreate(
                                                    ['initial' => trim($data['jenjang'])],
                                                    ['initial' => trim($data['jenjang']), 'name' => trim($data['jenjang'])]
                                                )->id
                                                : null,
                    'disiplin_ilmu'         => ($data['disp_ilmu']) ?? null,
                    'jurusan'               => ($data['jurusan']) ?? null,
                    'gelar'                 => ($data['gelar3']) ?? null,

                    'status_jemaat'         => $statusJemaat,
                    'region_id'             => (isset($data['Wilayah']) && !empty($data['Wilayah']))
                                                ? Region::firstOrCreate(
                                                    ['name' => trim($data['Wilayah']), 'organization_id' => $this->data['organization_id']],
                                                    ['name' => trim($data['Wilayah']), 'organization_id' => $this->data['organization_id']]
                                                )->id
                                                : null,
                    'cluster_id'            => null,
                    'organization_id'       => $this->data['organization_id'],
                ]);

                if (!empty($data['hobi'])) {
                    $hobiNames = array_map('trim', explode(',', $data['hobi']));
                    
                    $hobiIds = [];
                    foreach (explode(',', $data['hobi']) as $namaHobi) {
                        $hobi = \App\Models\Hobi::firstOrCreate([
                            'name' => trim($namaHobi),
                        ]);

                        $hobiIds[] = $hobi->id;
                    }
                    $anggota->hobis()->sync($hobiIds);
                }

                if (!empty($data['minat'])) {
                    $minatNames = array_map('trim', explode(',', $data['minat']));
                    
                    $minatIds = [];
                    foreach (explode(',', $data['minat']) as $namaMinat) {
                        $minat = \App\Models\Minat::firstOrCreate([
                            'name' => trim($namaMinat),
                        ]);

                        $minatIds[] = $minat->id;
                    }
                    $anggota->minats()->sync($minatIds);
                }

                // Simpan data baptis anak jika ada
                if ((!empty($data['baptis_tgl']) && $data['baptis_tgl'] != '  -   -') || !empty($data['baptis_di'])) {
                    $tempatBaptis = Organization::where('name', trim($data['baptis_di'] ?? ''))->value('id') ?? 0;
                    $anggota->baptisAnak()->updateOrCreate([], [
                        'jenis' => 'anak',
                        'tanggal' => ($data['baptis_tgl'] && $data['baptis_tgl'] != '  -   -') ? $this->tryParseDate($data['baptis_tgl']) : null,
                        'pendeta' => $data['baptis_oleh'] ?? null,
                        'tempat_baptis' => $tempatBaptis,
                        'gereja' => $data['baptis_di'] ?? null,
                        'alamat' => $data['bap_alamat'] ?? null,
                        'no_piagam' => $data['No Akte Baptis'] ?? null,
                    ]);
                }

                // Simpan data sidi jika ada
                if ((!empty($data['sidi_tgl']) && $data['sidi_tgl'] != '  -   -') || !empty($data['sidi_di'])) {
                    $tempatSidi = Organization::where('name', trim($data['sidi_di'] ?? ''))->value('id') ?? 0;
                    $anggota->baptisSidi()->updateOrCreate([], [
                        'jenis' => 'sidi',
                        'tanggal' => ($data['sidi_tgl'] && $data['sidi_tgl'] != '  -   -') ? $this->tryParseDate($data['sidi_tgl']) : null,
                        'pendeta' => $data['sidi_oleh'] ?? null,
                        'tempat_baptis' => $tempatSidi,
                        'gereja' => $data['sidi_di'] ?? null,
                        'alamat' => $data['sidi_alamat'] ?? null,
                        'no_piagam' => $data['No akte sidi'] ?? null,
                    ]);
                }

                if (!empty($data['nama_pasangan'])) {

                    $isLaki = strtolower($anggota->jenis_kelamin) === 'laki-laki';

                    $idPasangan = null;
                    $namaPasangan = trim($data['nama_pasangan'] ?? '');
                    $niaPasangan = trim($data['Anggt pasangan'] ?? '');

                    $niaPasangan = ($niaPasangan && is_numeric($niaPasangan)) ? 'NIA-' . $niaPasangan : NULL;

                    $dataPernikahan[$anggota->id] = [
                        'nia_suami' => $isLaki ? $anggota->nia : ($niaPasangan ?? null),
                        'nama_suami' => $isLaki ? $anggota->nama : ($namaPasangan ?? null),
                        'anggota_id_suami' => $isLaki ? $anggota->id : ($idPasangan ?? null),
                
                        'nia_istri' => !$isLaki ? $anggota->nia : ($niaPasangan ?? null),
                        'nama_istri' => !$isLaki ? $anggota->nama : ($namaPasangan ?? null),
                        'anggota_id_istri' => !$isLaki ? $anggota->id : ($idPasangan ?? null),
                
                        'no_akta_nikah'     => $data['no Akte nikah'] ?? null,
                        'tanggal_catatan_sipil' => $this->tryParseDate($data['sipil_tgl']),
                        'tempat_catatan_sipil'  => $data['sipil_kota'] ?? null,
                        'no_piagam'             => $data['sipil_noakte'] ?? null,
                        'tanggal_pemberkatan'   => $this->tryParseDate($data['nikah_tgl']),
                        'pendeta'               => $data['dinikahkan pendeta'] ?? null,
                        'gereja'            => $data['Menikah di'] ?? null,
                        'alamat_gereja'     => $data['nikah_alamat'] ?? null,
                    ];
                }

                if(!empty($data['n_ayah'])) {
                    $niaAyah = trim($data['angta ayah'] ?? '');
                    $namaAyah = trim($data['n_ayah'] ?? '');
                    $ayahId = null;
                    $niaAyah = ($niaAyah && is_numeric($niaAyah)) ? 'NIA-' . $niaAyah : NULL;
                    $dataOrangtua[] = [
                        'anggota_id' => $anggota->id,
                        'hubungan' => 'ayah',
                        'nia' => $niaAyah ?? null,
                        'nama' => $namaAyah ?? null,
                        'anak' => [
                            'anggota_id' => $anggota->id,
                            'nia'    => $anggota->nia,
                            'nama' => $anggota->nama,
                            'tempat_lahir'          => $anggota->tempat_lahir ?? null,
                            'tanggal_lahir'         => $this->tryParseDate($anggota->tanggal_lahir),
                            'jenis_kelamin'         => strtolower($anggota->jenis_kelamin),
                            'jemaat'                => null,
                            'alamat'                => $anggota->alamat_domisili,
                        ]
                    ];
                }
                
                if(!empty($data['n_ibu'])) {
                    $niaIbu = trim($data['anggota Ibu'] ?? '');
                    $ibuId = null;
                    $namaIbu = trim($data['n_ibu'] ?? '');
                    $niaIbu = ($niaIbu && is_numeric($niaIbu)) ? 'NIA-' . $niaIbu : NULL;
                    $dataOrangtua[] = [
                        'anggota_id' => $anggota->id,
                        'hubungan' => 'ibu',
                        'nia' => $niaIbu ?? null,
                        'nama' => $namaIbu ?? null,
                        'anak' => [
                            'anggota_id' => $anggota->id,
                            'nia'    => $anggota->nia,
                            'nama' => $anggota->nama,
                            'tempat_lahir'          => $anggota->tempat_lahir ?? null,
                            'tanggal_lahir'         => $this->tryParseDate($anggota->tanggal_lahir),
                            'jenis_kelamin'         => strtolower($anggota->jenis_kelamin),
                            'jemaat'                => null,
                            'alamat'                => $anggota->alamat_domisili,
                        ]
                    ];
                }

                $atestasiData = [
                    [
                        'tipe' => 'masuk',
                        'gereja_dari' => $data['atesasi 1 dari'] ?? null,
                        'alamat_asal' => $data['alamat Atesasi 1 dari'] ?? null,
                        'gereja_tujuan' => $data['Atesasi 1 ke'] ?? null,
                        'alamat_tujuan' => $data['alamat Atesasi 1 ke'] ?? null,
                        'tanggal' => $this->tryParseDate($data['Tgl Ateasi 1'] ?? null),
                    ],
                    [
                        'tipe' => 'masuk',
                        'gereja_dari' => $data['Atesasi  2 dari'] ?? null,
                        'alamat_asal' => $data['alamat Atesasi 2 dari'] ?? null,
                        'gereja_tujuan' => $data['Atesasi 2 ke'] ?? null,
                        'alamat_tujuan' => $data['alamat Atesasi 2 ke'] ?? null,
                        'tanggal' => $this->tryParseDate($data['tgl atesasi 2'] ?? null),
                    ],
                ];
            
                foreach ($atestasiData as $item) {
                    if ($item['gereja_dari'] || $item['gereja_tujuan']) {
                        Atestasi::updateOrCreate(
                            [
                                'anggota_id' => $anggota->id,
                            ],
                            array_merge($item, ['anggota_id' => $anggota->id])
                        );
                    }
                }

                if(!empty($data['Pekerjaan'])) {
                    $profesiName = trim(strtolower($data['Pekerjaan'] ?? ''));
                    $profesiId = null;

                    if ($profesiName) {
                        $profesiId = \App\Models\Profesi::firstOrCreate([
                            'name' => $profesiName,
                        ])->id;
                    }

                    \App\Models\Pekerjaan::updateOrCreate(
                        ['anggota_id' => $anggota->id],
                        [
                            'profesi_id' => $profesiId,
                        ]
                    );

                }

            }

            # import data keluarga
            if(!empty($dataPernikahan)) {
                foreach($dataPernikahan as $pernikahan) {
                    $existingMarriage = [];
                    if(!empty($pernikahan['anggota_id_suami'])) {
                        # data pernikahan anggota sebagai suami
                        if (!empty($pernikahan['nia_istri'])) {
                            $istri = Anggota::where('nia', $pernikahan['nia_istri'])->first();
                            if ($istri) {
                                $pernikahan['anggota_id_istri'] = $istri->id;
                                $pernikahan['nama_istri'] = $istri->nama;
                            }
                        } else {
                            if(!empty($pernikahan['nama_istri'])) {
                                $istri = Anggota::where('nama', $pernikahan['nama_istri'])->first();
                                if ($istri) {
                                    $pernikahan['anggota_id_istri'] = $istri->id;
                                    $pernikahan['nia_istri'] = $istri->nia;
                                }
                            }
                        }
                        $existingMarriage = Pernikahan::where('anggota_id_suami', $pernikahan['anggota_id_suami'])
                        ->first();
                    } else if(!empty($pernikahan['anggota_id_istri'])) {
                        # data pernikahan anggota sebagai istri
                        if (!empty($pernikahan['nia_suami'])) {
                            $suami = Anggota::where('nia', $pernikahan['nia_suami'])->first();
                            if ($suami) {
                                $pernikahan['anggota_id_suami'] = $suami->id;
                                $pernikahan['nama_suami'] = $suami->nama;
                            }
                        } else {
                            if(!empty($pernikahan['nama_suami'])) {
                                $suami = Anggota::where('nama', $pernikahan['nama_suami'])->first();
                                if ($suami) {
                                    $pernikahan['anggota_id_suami'] = $suami->id;
                                    $pernikahan['nia_suami'] = $suami->nia;
                                }
                            }
                        }
                        $existingMarriage = Pernikahan::where('anggota_id_istri', $pernikahan['anggota_id_istri'])
                        ->first();
                    }
                    if ($existingMarriage) {
                        $existingMarriage->update($pernikahan);
                    } else {
                        Pernikahan::create($pernikahan);
                    }
                }
            }

            if(!empty($dataOrangtua)) {
                foreach($dataOrangtua as $orangTua) {
                    $namaOrtu = $orangTua['nama'];
                    $ortuId = null;
                    if (!empty($orangTua['nia'])) {
                        $ortu = Anggota::where('nia', $orangTua['nia'])->first();
                        if ($ortu) {
                            $namaOrtu = $ortu->nama;
                            $ortuId = $ortu->id;
                        }
                    }

                    \App\Models\OrangTua::updateOrCreate(
                        [
                            'anggota_id' => $orangTua['anggota_id'],
                            'hubungan'    => $orangTua['hubungan'],
                        ],
                        [
                            'nia'   => $orangTua['nia'],
                            'nama'  => $orangTua['nama'],
                        ]
                    );

                    $dataAnak = $orangTua['anak'];
                    $bindAnak = [
                        'nia'               => $dataAnak['nia'],
                        'nama'              => $dataAnak['nama'],
                        'tempat_lahir'      => $dataAnak['tempat_lahir'],
                        'tanggal_lahir'     => $dataAnak['tanggal_lahir'],
                        'jenis_kelamin'     => $dataAnak['jenis_kelamin'],
                        'jemaat'            => $dataAnak['jemaat'],
                        'alamat'            => $dataAnak['alamat'],
                    ];
                    if($orangTua['hubungan'] == 'ayah' && !empty($ortuId)) {
                        $bindAnak['ayah_id'] = $ortuId;
                        \App\Models\Anak::updateOrCreate(
                            [
                                'anggota_id' => $dataAnak['anggota_id'],
                            ],
                            $bindAnak
                        );
                    }
                    if($orangTua['hubungan'] == 'ibu' && !empty($ortuId)) {
                        $bindAnak['ibu_id'] = $ortuId;
                        \App\Models\Anak::updateOrCreate(
                            [
                                'anggota_id' => $dataAnak['anggota_id'],
                            ],
                            $bindAnak
                        );
                    }
                }
            }

            Notification::make()
                ->title('Import berhasil')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Import gagal')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
