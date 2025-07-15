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

class EditAnggotaBaptis extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = AnggotaResource::class;
    protected static ?string $slug = '{record}/edit/data-baptis';
    protected static string $view = 'filament.resources.anggota-resource.pages.edit-anggota-baptis';

    public $record;
    public array $data = [];

    public function mount(Anggota $record): void
    {
        $this->record = $record;

        $this->form->fill([
            'baptisAnak' => $record->baptisAnak?->toArray() ?? [],
            'baptisSidi' => $record->baptisSidi?->toArray() ?? [],
        ]);
    }

    public function getTitle(): string
    {
        return 'Data Baptis';
    }

    public function getBreadcrumbs(): array
    {
        return [
            AnggotaResource::getUrl('index') => 'Anggota',
            url()->current() => 'Data Baptis',
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(AnggotaResource::dataBaptisForm())
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $state = $this->form->getState();

        // ---- BAPTIS ANAK ----
        $anak = $state['baptisAnak'] ?? [];

        $isAnakFilled = filled($anak['tempat_baptis'] ?? null)
            || filled($anak['tanggal'] ?? null)
            || filled($anak['gereja'] ?? null);

        $isAnakValid = filled($anak['tempat_baptis'] ?? null)
            && filled($anak['tanggal'] ?? null)
            && filled($anak['gereja'] ?? null);

        if ($isAnakFilled && ! $isAnakValid) {
            $this->addError('baptisAnak', 'Data Baptis Anak belum lengkap. Harap isi tempat baptis, tanggal, dan gereja.');
            Notification::make()
                ->title('Invalid Data')
                ->body('Data Baptis Anak belum lengkap. Harap isi tempat baptis, tanggal, dan gereja.')
                ->info()
                ->send();
            return;
        }

        if ($isAnakValid) {
            $this->record->baptisAnak()->updateOrCreate(
                ['jenis' => 'anak'],
                [
                    ...$anak,
                    'anggota_id' => $this->record->id,
                    'jenis' => 'anak',
                ]
            );
        }

        // ---- BAPTIS SIDI ----
        $sidi = $state['baptisSidi'] ?? [];

        $isSidiFilled = filled($sidi['tempat_baptis'] ?? null)
            || filled($sidi['tanggal'] ?? null)
            || filled($sidi['gereja'] ?? null);

        $isSidiValid = filled($sidi['tempat_baptis'] ?? null)
            && filled($sidi['tanggal'] ?? null)
            && filled($sidi['gereja'] ?? null);

        if ($isSidiFilled && ! $isSidiValid) {
            $this->addError('baptisSidi', 'Data Baptis Sidi belum lengkap. Harap isi tempat baptis, tanggal, dan gereja.');
            Notification::make()
                ->title('Invalid Data')
                ->body('Data Baptis Sidi belum lengkap. Harap isi tempat baptis, tanggal, dan gereja.')
                ->info()
                ->send();
            return;
        }

        if ($isSidiValid) {
            $this->record->baptisSidi()->updateOrCreate(
                ['jenis' => 'sidi'],
                [
                    ...$sidi,
                    'anggota_id' => $this->record->id,
                    'jenis' => 'sidi',
                ]
            );
        }

        if ($isAnakValid || $isSidiValid) {
            Notification::make()
                ->title('Saved')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Invalid Data')
                ->info()
                ->send();
        }

        $this->redirect(AnggotaResource::getUrl('edit-baptis', ['record' => $this->record]));
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
