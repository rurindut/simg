<?php

namespace App\Filament\Resources\AnggotaResource\Pages;

use App\Filament\Resources\AnggotaResource;
use Filament\Resources\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use App\Models\Anggota;
use Filament\Forms\Form;
use Filament\Pages\Actions;
use Filament\Notifications\Notification;

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
            'pasangan' => [
                'id' => $record->pasangan?->id,
                'nia' => $record->pasangan?->nia,
                'nama' => $record->pasangan?->nama,
                'no_akta_nikah' => $record->pasangan?->no_akta_nikah,
                'tanggal_catatan_sipil' => $record->pasangan?->tanggal_catatan_sipil,
                'tempat_catatan_sipil' => $record->pasangan?->tempat_catatan_sipil,
                'no_piagam' => $record->pasangan?->no_piagam,
                'tanggal_pemberkatan' => $record->pasangan?->tanggal_pemberkatan,
                'pendeta' => $record->pasangan?->pendeta,
                'gereja' => $record->pasangan?->gereja,
                'alamat_gereja' => $record->pasangan?->alamat_gereja,
                'akta_catatan_sipil' => $record->pasangan?->akta_catatan_sipil,
                'piagam_pemberkatan' => $record->pasangan?->piagam_pemberkatan,
            ],
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

        // if (!empty($data['ayah']['nia']) && !Anggota::where('nia', $data['ayah']['nia'])->exists()) {
        //     Anggota::create([
        //         'nia' => $data['ayah']['nia'],
        //         'nama' => $data['ayah']['nama'],
        //         'jenis_kelamin' => 'Laki-laki',
        //         'organization_id' => $this->record->organization_id,
        //     ]);
        // }
    
        // if (!empty($data['ibu']['nia']) && !Anggota::where('nia', $data['ibu']['nia'])->exists()) {
        //     Anggota::create([
        //         'nia' => $data['ibu']['nia'],
        //         'nama' => $data['ibu']['nama'],
        //         'jenis_kelamin' => 'Perempuan',
        //         'organization_id' => $this->record->organization_id,
        //     ]);
        // }    

        // Simpan Ayah
        $this->record->ayah()->updateOrCreate(
            ['hubungan' => 'ayah'],
            [
                'nia' => $data['ayah']['nia'] ?? null,
                'nama' => $data['ayah']['nama'] ?? null,
            ]
        );

        // Simpan Ibu
        $this->record->ibu()->updateOrCreate(
            ['hubungan' => 'ibu'],
            [
                'nia' => $data['ibu']['nia'] ?? null,
                'nama' => $data['ibu']['nama'] ?? null,
            ]
        );

        if (
            !empty($data['pasangan']) && 
                filled($data['pasangan']['nama']) &&
                filled($data['pasangan']['no_akta_nikah'])
        ) {
            // if (
            //     !empty($data['pasangan']['nia']) &&
            //     !Anggota::where('nia', $data['pasangan']['nia'])->exists()
            // ) {
            //     $jenisKelaminPasangan = $this->record->jenis_kelamin === 'Laki-laki' ? 'Perempuan' : 'Laki-laki';
        
            //     Anggota::create([
            //         'nia' => $data['pasangan']['nia'],
            //         'nama' => $data['pasangan']['nama'],
            //         'jenis_kelamin' => $jenisKelaminPasangan,
            //         'organization_id' => $this->record->organization_id,
            //     ]);
            // }
            $this->record->pasangan()->updateOrCreate(
                [],
                [
                    'nia' => $data['pasangan']['nia'] ?? null,
                    'nama' => $data['pasangan']['nama'] ?? null,
                    'no_akta_nikah' => $data['pasangan']['no_akta_nikah'] ?? null,
                    'tanggal_catatan_sipil' => $data['pasangan']['tanggal_catatan_sipil'] ?? null,
                    'tempat_catatan_sipil' => $data['pasangan']['tempat_catatan_sipil'] ?? null,
                    'no_piagam' => $data['pasangan']['no_piagam'] ?? null,
                    'tanggal_pemberkatan' => $data['pasangan']['tanggal_pemberkatan'] ?? null,
                    'pendeta' => $data['pasangan']['pendeta'] ?? null,
                    'gereja' => $data['pasangan']['gereja'] ?? null,
                    'alamat_gereja' => $data['pasangan']['alamat_gereja'] ?? null,
                    'akta_catatan_sipil' => $data['pasangan']['akta_catatan_sipil'] ?? null,
                    'piagam_pemberkatan' => $data['pasangan']['piagam_pemberkatan'] ?? null,
                ]
            );
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
