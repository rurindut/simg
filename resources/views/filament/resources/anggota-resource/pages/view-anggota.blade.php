<x-filament::page>
    <div class="flex gap-6">
        {{-- Panel Kiri --}}
        <div class="w-72 p-4 rounded-xl shadow border filament-border divide-y">
            <div class="text-center pb-4">
                <h2 class="text-base font-medium text-foreground">{{ $record->nama }}</h2>
                <div class="text-sm text-muted">{{ $record->status ?? '-' }}</div>
            </div>

            <div class="pt-2 text-sm space-y-3">
                <div class="pt-2">
                    <div class="font-medium text-muted">NIA</div>
                    <div class="text-foreground">{{ $record->nia ?? '-' }}</div>
                </div>
                <div class="pt-2">
                    <div class="font-medium text-muted">Email</div>
                    <div class="text-foreground">{{ $record->email ?? '-' }}</div>
                </div>
                <div class="pt-2">
                    <div class="font-medium text-muted">No Hp</div>
                    <div class="text-foreground">{{ $record->nomor_hp ?? '-' }}</div>
                </div>
                <div class="pt-2">
                    <div class="font-medium text-muted">Wilayah</div>
                    <div class="text-foreground">{{ $record->wilayah?->nama ?? '-' }}</div>
                </div>
                <div class="pt-2">
                    <div class="font-medium text-muted">Kelompok</div>
                    <div class="text-foreground">{{ $record->kelompok?->nama ?? '-' }}</div>
                </div>
                <div class="pt-2">
                    <div class="font-medium text-muted">Alamat</div>
                    <div class="text-foreground">{{ $record->alamat ?? '-' }}</div>
                </div>
                <div class="pt-2">
                    <div class="font-medium text-muted">Suku</div>
                    <div class="text-foreground">{{ $record->suku?->nama ?? '-' }}</div>
                </div>
                <div class="pt-2">
                    <div class="font-medium text-muted">Hobi</div>
                    <div class="text-foreground">{{ $record->hobi?->nama ?? '-' }}</div>
                </div>
            </div>
        </div>


        {{-- Tabs --}}
        <div x-data="{ tab: 'pribadi' }" class="space-y-4">

            {{-- Tab Header --}}
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                    <template x-for="(label, key) in {
                        pribadi: 'Data Pribadi',
                        baptis: 'Data Baptis',
                        atestasi: 'Data Atestasi',
                        keluarga: 'Data Keluarga',
                        aktivitas: 'Pengalaman/Pekerjaan/Aktivitas Sosial'
                    }" :key="key">
                        <button
                            type="button"
                            @click="tab = key"
                            :class="{
                                'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': tab === key,
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:border-gray-600': tab !== key
                            }"
                            class="whitespace-nowrap border-b-2 px-3 py-2 text-sm font-medium transition"
                            x-text="label"
                        ></button>
                    </template>
                </nav>
            </div>

            {{-- Tab Content Card --}}
            <x-filament::card>
                <div x-show="tab === 'pribadi'" x-cloak>
                    @include('filament.resources.anggota-resource.includes.data-pribadi')
                </div>
                <div x-show="tab === 'baptis'" x-cloak>
                    @include('filament.resources.anggota-resource.includes.data-baptis')
                </div>
                <div x-show="tab === 'atestasi'" x-cloak>
                    @include('filament.resources.anggota-resource.includes.data-atestasi')
                </div>
                <div x-show="tab === 'keluarga'" x-cloak>
                    @include('filament.resources.anggota-resource.includes.data-keluarga')
                </div>
                <div x-show="tab === 'aktivitas'" x-cloak>
                    @include('filament.resources.anggota-resource.includes.data-aktivitas')
                </div>
            </x-filament::card>
        </div>
    </div>
</x-filament::page>
