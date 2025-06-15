<x-filament::page>
    <div class="grid grid-cols-12 gap-4" x-data="{ tab: 'pribadi' }">
        {{-- Sidebar Kiri --}}
        <div class="col-span-12 md:col-span-4 bg-white shadow p-4 rounded-xl space-y-3">
            <div class="text-center font-bold text-lg">
                {{ $record->nama }}
            </div>
            <div class="text-center text-gray-500">
                {{ $record->status_jemaat }}
            </div>

            <hr class="my-4 border-gray-300" />

            {{-- Ganti key-value dengan HTML manual --}}
            <div class="space-y-2 text-sm">
                <div><strong>NIA:</strong> {{ $record->nia }}</div>
                <div><strong>Email:</strong> {{ $record->email }}</div>
                <div><strong>No HP:</strong> {{ $record->nomor_hp }}</div>
                <div><strong>Wilayah:</strong> {{ $record->region?->name ?? 'Menunggu' }}</div>
                <div><strong>Kelompok:</strong> {{ $record->cluster?->name ?? 'None' }}</div>
                <div><strong>Alamat:</strong> {{ $record->alamat_ktp }}</div>
                <div><strong>Suku:</strong> {{ $record->suku?->name ?? 'Kosong' }}</div>
                <div><strong>Hobi:</strong> {{ $record->hobi?->name ?? 'Kosong' }}</div>
                <div><strong>Minat:</strong> {{ $record->minat?->name ?? 'Kosong' }}</div>
            </div>
        </div>

        {{-- Panel Kanan --}}
        <div class="col-span-12 md:col-span-8 bg-white shadow rounded-xl p-4">
            {{-- Tab Header --}}
            <div class="flex space-x-2 border-b border-gray-200 mb-4">
                <button @click="tab = 'pribadi'" :class="{ 'border-b-2 font-semibold': tab === 'pribadi' }" class="px-4 py-2 hover:text-primary-600">Data Pribadi</button>
                <button @click="tab = 'baptis'" :class="{ 'border-b-2 font-semibold': tab === 'baptis' }" class="px-4 py-2 hover:text-primary-600">Data Baptis</button>
                <button @click="tab = 'atestasi'" :class="{ 'border-b-2 font-semibold': tab === 'atestasi' }" class="px-4 py-2 hover:text-primary-600">Data Atestasi</button>
                <button @click="tab = 'keluarga'" :class="{ 'border-b-2 font-semibold': tab === 'keluarga' }" class="px-4 py-2 hover:text-primary-600">Data Keluarga</button>
                <button @click="tab = 'aktivitas'" :class="{ 'border-b-2 font-semibold': tab === 'aktivitas' }" class="px-4 py-2 hover:text-primary-600">Aktivitas Sosial</button>
            </div>
        </div>
    </div>
</x-filament::page>
