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
use Illuminate\Support\Facades\Log;

class EditAnggotaAktivitas extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = AnggotaResource::class;
    protected static ?string $slug = '{record}/edit/data-aktivitas';
    protected static string $view = 'filament.resources.anggota-resource.pages.edit-anggota-aktivitas';

    public $record;
    public array $data = [];

    public function mount(Anggota $record): void
    {
        $this->record = $record;

        $this->form->fill([
            'pengalamanGerejawis' => $record->pengalamanGerejawis->toArray(),
            'aktivitasSosials' => $record->aktivitasSosials->toArray(),
            'pekerjaans' => $record->pekerjaans->toArray(),
        ]);
    }

    public function getTitle(): string
    {
        return 'Data Aktivitas';
    }

    public function getBreadcrumbs(): array
    {
        return [
            AnggotaResource::getUrl('index') => 'Anggota',
            url()->current() => 'Data Aktivitas',
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(
                AnggotaResource::dataActivitasForm()
            )
            ->statePath('data')
            ->model($this->record);
    }

    public function save()
    {
        $data = $this->form->getState();
        
        if (!empty($data['pengalamanGerejawis'])) {
            $this->record->pengalamanGerejawis()->createMany($data['pengalamanGerejawis']);
        }
    
        if (!empty($data['aktivitasSosials'])) {
            $this->record->aktivitasSosials()->createMany($data['aktivitasSosials']);
        }
    
        if (!empty($data['pekerjaans'])) {
            $this->record->pekerjaans()->createMany($data['pekerjaans']);
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
