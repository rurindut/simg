<x-filament::page>
    <x-anggota-tabs-nav :anggota="$record" class="mb-4" />
    <form wire:submit.prevent="save" class="space-y-6">
    {{ $this->form }}
    <x-filament::button type="submit">Simpan</x-filament::button>
    <a href="{{ \App\Filament\Resources\AnggotaResource::getUrl('index') }}">
        <x-filament::button color="gray">
            Cancel
        </x-filament::button>
    </a>
    </form>
</x-filament::page>
