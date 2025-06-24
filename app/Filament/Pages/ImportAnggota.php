<?php

namespace App\Filament\Pages;

use App\Models\Anggota;
use App\Models\Atestasi;
use App\Models\Organization;
use App\Models\Pekerjaan;
use App\Models\Pendidikan;
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
                    $niaPasangan = trim($data['Anggt pasangan'] ?? '');
                    $pasanganId = null;
                    $namaPasangan = trim($data['nama_pasangan'] ?? '');

                    // $pasanganNia = '';
                    if ($niaPasangan !== '') {
                        $pasanganNia = 'NIA-' . $niaPasangan;
                        $pasangan = Anggota::where('nia', $pasanganNia)->first();
                        if ($pasangan) {
                            // $pasanganId = $pasangan->id;
                            $niaPasangan = $pasanganNia;
                            $namaPasangan = $pasangan->nama;
                        } else {
                            $niaPasangan = '';
                        }
                    }

                    // Simpan data pasangan
                    $anggota->pasangan()->updateOrCreate([], [
                        'nia'               => $niaPasangan ?? null,
                        'nama'              => $namaPasangan ?? null,
                        'no_akta_nikah'     => $data['no Akte nikah'] ?? null,
                        'tanggal_catatan_sipil' => $this->tryParseDate($data['sipil_tgl']),
                        'tempat_catatan_sipil'  => $data['sipil_kota'] ?? null,
                        'no_piagam'             => $data['sipil_noakte'] ?? null,
                        'tanggal_pemberkatan'   => $this->tryParseDate($data['nikah_tgl']),
                        'pendeta'               => $data['dinikahkan pendeta'] ?? null,
                        'gereja'            => $data['Menikah di'] ?? null,
                        'alamat_gereja'     => $data['nikah_alamat'] ?? null,
                    ]);
                }

                if(!empty($data['n_ayah'])) {
                    $niaAyah = trim($data['angta ayah'] ?? '');
                    $namaAyah = trim($data['n_ayah'] ?? '');
                    $ayahId = null;

                    if ($niaAyah !== '') {
                        $ayahNia = 'NIA-' . $niaAyah;
                        $ayah = Anggota::where('nia', $ayahNia)->first();
                        if ($ayah) {
                            $niaAyah = $ayahNia;
                            $namaAyah = $ayah->nama;
                            $ayahId = $ayah->id;
                        } else {
                            $niaAyah = '';
                        }
                    }

                    $anggota->ayah()->updateOrCreate(
                        ['hubungan' => 'ayah'],
                        [
                            'nia' => $niaAyah ?? null,
                            'nama' => $namaAyah ?? null,
                        ]
                    );
                }

                if(!empty($data['n_ibu'])) {
                    $niaIbu = trim($data['anggota Ibu'] ?? '');
                    $ibuId = null;
                    $namaIbu = trim($data['n_ibu'] ?? '');

                    if ($niaIbu !== '') {
                        $ibuNia = 'NIA-' . $niaIbu;
                        $ibu = Anggota::where('nia', $ibuNia)->first();
                        if ($ibu) {
                            $niaIbu = $ibuNia;
                            $namaIbu = $ibu->nama;
                        } else {
                            $niaIbu = '';
                        }
                    }

                    $anggota->ibu()->updateOrCreate(
                        ['hubungan' => 'ibu'],
                        [
                            'nia' => $niaIbu ?? null,
                            'nama' => $namaIbu ?? null,
                        ]
                    );
                }

                if(!empty($data['Kel.'])) {
                    if($niaAyah == $data['Kel.']) {
                        if ($ayahId) {
                            \App\Models\Anak::updateOrCreate(
                                [
                                    'anggota_id' => $ayahId,
                                    'nia'    => $anggota->nia,
                                ],
                                [
                                    'nama' => $anggota->nama,
                                    'tempat_lahir'          => $anggota->tempat_lahir ?? null,
                                    'tanggal_lahir'         => $this->tryParseDate($anggota->tanggal_lahir),
                                    'jenis_kelamin'         => strtolower($anggota->jenis_kelamin),
                                    'jemaat'                => null,
                                    'alamat'                => $anggota->alamat_domisili,
                        
                                ]
                            );
                        }
                    } else if($niaIbu == $data['Kel.']) {
                        if ($ibuId) {
                            \App\Models\Anak::updateOrCreate(
                                [
                                    'anggota_id' => $ibuId,
                                    'nia'    => $anggota->nia,
                                ],
                                [
                                    'nama' => $anggota->nama,
                                    'tempat_lahir'          => $anggota->tempat_lahir ?? null,
                                    'tanggal_lahir'         => $this->tryParseDate($anggota->tanggal_lahir),
                                    'jenis_kelamin'         => strtolower($anggota->jenis_kelamin),
                                    'jemaat'                => null,
                                    'alamat'                => $anggota->alamat_domisili,
                        
                                ]
                            );
                        }
                    }
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
