<x-filament::page>
    <div x-data="{ tab: 'pribadi' }" class="space-y-4">

        {{-- Tab Header --}}
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button
                    @click="tab = 'pribadi'"
                    :class="tab === 'pribadi' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium"
                >
                    Data Pribadi
                </button>

                <button
                    @click="tab = 'baptis'"
                    :class="tab === 'baptis' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium"
                >
                    Data Baptis
                </button>

                <button
                    @click="tab = 'atestasi'"
                    :class="tab === 'atestasi' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium"
                >
                    Data Atestasi
                </button>

                <button
                    @click="tab = 'keluarga'"
                    :class="tab === 'keluarga' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium"
                >
                    Data Keluarga
                </button>

                <button
                    @click="tab = 'aktivitas'"
                    :class="tab === 'aktivitas' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium"
                >
                    Aktivitas
                </button>
            </nav>
        </div>

        {{-- Tab Contents --}}
        <div x-show="tab === 'pribadi'" class="p-4">
            @include('filament.resources.anggota-resource.includes.data-pribadi')
        </div>

        <div x-show="tab === 'baptis'" class="p-4" x-cloak>
            @include('filament.resources.anggota-resource.includes.data-baptis')
        </div>

        <div x-show="tab === 'atestasi'" class="p-4" x-cloak>
            @include('filament.resources.anggota-resource.includes.data-atestasi')
        </div>

        <div x-show="tab === 'keluarga'" class="p-4" x-cloak>
            @include('filament.resources.anggota-resource.includes.data-keluarga')
        </div>

        <div x-show="tab === 'aktivitas'" class="p-4" x-cloak>
            @include('filament.resources.anggota-resource.includes.data-aktivitas')
        </div>
    </div>
</x-filament::page>
