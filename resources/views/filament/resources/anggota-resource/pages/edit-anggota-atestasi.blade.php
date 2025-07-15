<x-filament::page>
    @php
        $currentBrowserPath = 'data-atestasi';
    @endphp
    <x-anggota-tabs-nav :anggota="$record" :currentBrowserPath="$currentBrowserPath" class="mb-4" />
    {{ $this->table }}
</x-filament::page>
