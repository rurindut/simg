<?php

namespace App\Filament\Resources\AnggotaResource\Pages;

use App\Filament\Resources\AnggotaResource;
use App\Models\Anggota;
use App\Models\Pernikahan;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Log;

class EditAnggotaKeluarga extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = AnggotaResource::class;
    protected static ?string $slug = '{record}/edit/data-keluarga';
    protected static string $view = 'filament.resources.anggota-resource.pages.edit-anggota-keluarga';

    public $record;
    public array $data = [];

    public function mount(Anggota $record): void
    {
        $this->record = $record;

        $pernikahan = \App\Models\Pernikahan::where('anggota_id_suami', $record->id)
            ->orWhere('anggota_id_istri', $record->id)
            ->first();

        $dataPernikahan = [];
        if($pernikahan) {
            $isSuami = $pernikahan->anggota_id_suami === $record->id;

            $niaPasangan = $isSuami ? $pernikahan->nia_istri : $pernikahan->nia_suami;
            $namaPasangan = $isSuami ? $pernikahan->nama_istri : $pernikahan->nama_suami;

            $dataPernikahan = [
                'id' => $record->id,
                'nia' => $niaPasangan,
                'nama' => $namaPasangan,
                'no_akta_nikah' => $pernikahan->no_akta_nikah,
                'tanggal_catatan_sipil' => $pernikahan->tanggal_catatan_sipil,
                'tempat_catatan_sipil' => $pernikahan->tempat_catatan_sipil,
                'no_piagam' => $pernikahan->no_piagam,
                'tanggal_pemberkatan' => $pernikahan->tanggal_pemberkatan,
                'pendeta' => $pernikahan->pendeta,
                'gereja' => $pernikahan->gereja,
                'alamat_gereja' => $pernikahan->alamat_gereja,
                'akta_catatan_sipil' => $pernikahan->akta_catatan_sipil,
                'piagam_pemberkatan' => $pernikahan->piagam_pemberkatan,
            ];
        }

        $dataAnak = [];
        $anaks = $record->semuaAnak;
        foreach ($record->semuaAnak as $anak) {
            $dataAnak[] = [
                'nia' => $anak->nia,
                'nama' => $anak->nama,
                'tempat_lahir' => $anak->tempat_lahir,
                'tanggal_lahir' => $anak->tanggal_lahir,
                'jenis_kelamin' => $anak->jenis_kelamin,
                'jemaat' => $anak->jemaat,
                'alamat' => $anak->alamat,
                'nama_disabled' => filled($anak->nia),
            ];
        }

        $this->form->fill([
            'ayah' => [
                'id' => $record->ayah?->id,
                'hubungan' => 'ayah',
                'nia' => $record->ayah?->nia,
                'nama' => $record->ayah?->nama,
            ],
            'ibu' => [
                'id' => $record->ibu?->id,
                'hubungan' => 'ibu',
                'nia' => $record->ibu?->nia,
                'nama' => $record->ibu?->nama,
            ],
            'pasangan' => $dataPernikahan,
            'data_anak' => $dataAnak
        ]);
    }

    public function getTitle(): string
    {
        return 'Data Keluarga';
    }

    public function getBreadcrumbs(): array
    {
        return [
            AnggotaResource::getUrl('index') => 'Anggota',
            url()->current() => 'Data Keluarga',
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(
                AnggotaResource::dataKeluargaForm()
            )
            ->statePath('data')
            ->model($this->record);
    }

    public function save()
    {
        $data = $this->form->getState();
        // Log::debug($data);
        $bindAnak = [
            'nia'               => $this->record->nia,
            'nama'              => $this->record->nama,
            'tempat_lahir'      => $this->record->tempat_lahir,
            'tanggal_lahir'     => $this->record->tanggal_lahir,
            'jenis_kelamin'     => $this->record->jenis_kelamin,
            'jemaat'            => NULL,
            'alamat'            => $this->record->alamat_domisili,
        ];

        // Simpan Ayah
        if (empty($data['ayah']['nia']) && empty($data['ayah']['nama'])) {
            $this->record->ayah()?->delete();
        }
        if(!empty($data['ayah']['nama'])) {
            $ayah = $this->record->ayah()->updateOrCreate(
                ['hubungan' => 'ayah'],
                [
                    'nia' => $data['ayah']['nia'] ?? null,
                    'nama' => $data['ayah']['nama'] ?? null,
                ]
            );

            if (filled($data['ayah']['nia'])) {
                $dataAyah = Anggota::where('nia', $data['ayah']['nia'])->first();
                if ($dataAyah) {
                    $bindAnak['ayah_id'] = $dataAyah->id;
                    \App\Models\Anak::updateOrCreate(
                        [
                            'anggota_id' => $this->record->id,
                        ],
                        $bindAnak
                    );
                }
            }
        }

        // Simpan Ibu
        if (empty($data['ibu']['nia']) && empty($data['ibu']['nama'])) {
            $this->record->ibu()?->delete();
        }
        if(!empty($data['ibu']['nama'])) {
            $ibu = $this->record->ibu()->updateOrCreate(
                ['hubungan' => 'ibu'],
                [
                    'nia' => $data['ibu']['nia'] ?? null,
                    'nama' => $data['ibu']['nama'] ?? null,
                ]
            );

            if (filled($data['ibu']['nia'])) {
                $dataIbu = Anggota::where('nia', $data['ibu']['nia'])->first();
                if ($dataIbu) {
                    $bindAnak['ibu_id'] = $dataIbu->id;
                    \App\Models\Anak::updateOrCreate(
                        [
                            'anggota_id' => $this->record->id,
                        ],
                        $bindAnak
                    );
                }
            }
        }

        if (empty($data['pasangan']) || (empty($data['pasangan']['nama']) && empty($data['pasangan']['nia'])) ) {
            $isLaki = $this->record->jenis_kelamin === 'Laki-laki';

            Pernikahan::where(
                $isLaki ? 'anggota_id_suami' : 'anggota_id_istri',
                $this->record->id
            )->delete();
        }
        if (
            !empty($data['pasangan']) && 
                filled($data['pasangan']['nama']) 
                // && filled($data['pasangan']['no_akta_nikah'])
        ) {
            $isLaki = $this->record->jenis_kelamin === 'Laki-laki';

            $pasanganAnggota = null;
            if (filled($data['pasangan']['nia'])) {
                $pasanganAnggota = Anggota::where('nia', $data['pasangan']['nia'])->first();
            }

            // Satu pernikahan per anggota
            $existingMarriage = Pernikahan::where(
                $isLaki ? 'anggota_id_suami' : 'anggota_id_istri',
                $this->record->id
            )->first();

            $pernikahanData = [
                'nia_suami' => $isLaki ? $this->record->nia : ($data['pasangan']['nia'] ?? null),
                'nama_suami' => $isLaki ? $this->record->nama : ($data['pasangan']['nama'] ?? null),
                'anggota_id_suami' => $isLaki ? $this->record->id : ($pasanganAnggota?->id ?? null),
        
                'nia_istri' => !$isLaki ? $this->record->nia : ($data['pasangan']['nia'] ?? null),
                'nama_istri' => !$isLaki ? $this->record->nama : ($data['pasangan']['nama'] ?? null),
                'anggota_id_istri' => !$isLaki ? $this->record->id : ($pasanganAnggota?->id ?? null),
        
                'no_akta_nikah' => $data['pasangan']['no_akta_nikah'] ?? null,
                'tanggal_catatan_sipil' => $data['pasangan']['tanggal_catatan_sipil'] ?? null,
                'tempat_catatan_sipil' => $data['pasangan']['tempat_catatan_sipil'] ?? null,
                'akta_catatan_sipil' => $data['pasangan']['akta_catatan_sipil'] ?? null,
                'no_piagam' => $data['pasangan']['no_piagam'] ?? null,
                'tanggal_pemberkatan' => $data['pasangan']['tanggal_pemberkatan'] ?? null,
                'pendeta' => $data['pasangan']['pendeta'] ?? null,
                'gereja' => $data['pasangan']['gereja'] ?? null,
                'alamat_gereja' => $data['pasangan']['alamat_gereja'] ?? null,
                'piagam_pemberkatan' => $data['pasangan']['piagam_pemberkatan'] ?? null,
            ];
        
            if ($existingMarriage) {
                $existingMarriage->update($pernikahanData);
            } else {
                Pernikahan::create($pernikahanData);
            }
        }

        if(!empty($data['data_anak'])) {
            \App\Models\Anak::where('ayah_id', $this->record->id)
                ->orWhere('ibu_id', $this->record->id)
                ->delete();
            foreach ($data['data_anak'] as $anak) {
                $bindAnakAnggota = [
                    'nia' => $anak['nia'],
                    'nama' => $anak['nama'],
                    'tempat_lahir' => $anak['tempat_lahir'],
                    'tanggal_lahir' => $anak['tanggal_lahir'],
                    'jenis_kelamin' => $anak['jenis_kelamin'],
                    'jemaat' => $anak['jemaat'],
                    'alamat' => $anak['alamat'],
                ];
                $isLaki = $this->record->jenis_kelamin === 'Laki-laki';
                $existingMarriage = Pernikahan::where(
                    $isLaki ? 'anggota_id_suami' : 'anggota_id_istri',
                    $this->record->id
                )->first();
                if($isLaki) {
                    $bindAnakAnggota['ayah_id'] = $this->record->id;
                    $bindAnakAnggota['ibu_id'] = ($existingMarriage->anggota_id_istri) ?? null;
                } else {
                    $bindAnakAnggota['ibu_id'] = $this->record->id;
                    $bindAnakAnggota['ayah_id'] = ($existingMarriage->anggota_id_suami) ?? null;
                }
                if(!empty($anak['nia'])) {
                    $anakAnggota = Anggota::where('nia', $anak['nia'])->first();
                    if($anakAnggota) {   
                        $bindAnakAnggota['anggota_id'] = $anakAnggota->id;
                    }
                }
                // Log::debug($bindAnakAnggota);
                \App\Models\Anak::create($bindAnakAnggota);
            }
        } else {
            \App\Models\Anak::where('ayah_id', $this->record->id)
                ->orWhere('ibu_id', $this->record->id)
                ->delete();
        }

        Notification::make()
            ->success()
            ->title('Saved')
            ->send();
    }

    protected function getActions(): array
    {
        return [
            // 
        ];
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

}
