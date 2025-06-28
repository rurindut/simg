<div class="space-y-6">

    {{-- ORANG TUA --}}
    <x-filament::section>
        <x-slot name="heading">Orang Tua</x-slot>
        <x-slot name="description">Data ayah dan ibu dari anggota ini.</x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if ($record->ayah)
                <div class="filament-card space-y-1">
                    <div class="font-medium text-gray-900 dark:text-gray-100">Ayah</div>
                    <div class="text-foreground"><strong>NIA:</strong> {{ $record->ayah->nia ?? '-' }}</div>
                    <div class="text-foreground"><strong>Nama:</strong> {{ $record->ayah->nama ?? '-' }}</div>
                </div>
            @endif

            @if ($record->ibu)
                <div class="filament-card space-y-1">
                    <div class="font-medium text-gray-900 dark:text-gray-100">Ibu</div>
                    <div class="text-foreground"><strong>NIA:</strong> {{ $record->ibu->nia ?? '-' }}</div>
                    <div class="text-foreground"><strong>Nama:</strong> {{ $record->ibu->nama ?? '-' }}</div>
                </div>
            @endif
        </div>
    </x-filament::section>

    {{-- PASANGAN --}}
    @php
        $pernikahan = $record->pernikahan_aktif ?? null;
    @endphp

    @if ($pernikahan)
    <x-filament::section>
        <x-slot name="heading">Pasangan</x-slot>
        <x-slot name="description">Data pasangan dari anggota ini.</x-slot>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="space-y-2">
                <p><span class="font-semibold">NIA:</span> <br> 
                    {{ $record->jenis_kelamin === 'Laki-laki' ? $pernikahan->nia_istri : $pernikahan->nia_suami ?? '-' }}
                </p>
            </div>
            <div class="space-y-2">
                <p><span class="font-semibold">Nama Pasangan:</span> <br> 
                    {{ $record->jenis_kelamin === 'Laki-laki' ? $pernikahan->nama_istri : $pernikahan->nama_suami ?? '-' }}
                </p>
            </div>
            <div class="space-y-2">
                <p><span class="font-semibold">No. Akta Nikah:</span> <br> {{ $pernikahan->no_akta_nikah ?? '-' }}</p>
            </div>
            <div class="space-y-2">
                <p><span class="font-semibold">Tanggal Catatan Sipil:</span> <br> {{ $pernikahan->tanggal_catatan_sipil ? \Carbon\Carbon::parse($pernikahan->tanggal_catatan_sipil)->format('d-m-Y') : '-' }}</p>
            </div>
            <div class="space-y-2">
                <p><span class="font-semibold">Tempat Catatan Sipil:</span> <br> {{ $pernikahan->tempat_catatan_sipil ?? '-' }}</p>
            </div>
            <div class="space-y-2">
                <p><span class="font-semibold">No. Piagam:</span> <br> {{ $pernikahan->no_piagam ?? '-' }}</p>
            </div>
            <div class="space-y-2">
                <p><span class="font-semibold">Tanggal Pemberkatan:</span> <br> {{ $pernikahan->tanggal_pemberkatan ? \Carbon\Carbon::parse($pernikahan->tanggal_pemberkatan)->format('d-m-Y') : '-' }}</p>
            </div>
            <div class="space-y-2">
                <p><span class="font-semibold">Pendeta:</span> <br> {{ $pernikahan->pendeta ?? '-' }}</p>
            </div>
            <div class="space-y-2">
                <p><span class="font-semibold">Gereja:</span> <br> {{ $pernikahan->gereja ?? '-' }}</p>
            </div>
            <div class="space-y-2">
                <p><span class="font-semibold">Alamat Gereja:</span> <br> {{ $pernikahan->alamat_gereja ?? '-' }}</p>
            </div>
        </div>
        {{-- Lampiran Akta dan Piagam --}}
        <div class="mt-6">
            <div class="flex flex-wrap gap-6">
                @if ($pernikahan->akta_catatan_sipil)
                    <div class="w-32">
                        <label class="font-bold text-sm text-gray-700 block mb-2">Akta Catatan Sipil</label>
                        <div class="w-32 h-32 rounded-xl overflow-hidden border shadow">
                            <img src="{{ Storage::url($pernikahan->akta_catatan_sipil) }}" alt="Akta Catatan Sipil" class="object-cover w-full h-full" />
                        </div>
                        <p class="text-xs text-center mt-1 text-gray-600">Akta Catatan Sipil</p>
                    </div>
                @endif

                @if ($pernikahan->piagam_pemberkatan)
                    <div class="w-32">
                        <label class="font-bold text-sm text-gray-700 block mb-2">Piagam Pemberkatan</label>
                        <div class="w-32 h-32 rounded-xl overflow-hidden border shadow">
                            <img src="{{ Storage::url($pernikahan->piagam_pemberkatan) }}" alt="Piagam Pemberkatan" class="object-cover w-full h-full" />
                        </div>
                        <p class="text-xs text-center mt-1 text-gray-600">Piagam Pemberkatan</p>
                    </div>
                @endif
            </div>
        </div>
    </x-filament::section>
    @endif

    {{-- ANAK --}}
    <x-filament::section>
        <x-slot name="heading">Anak</x-slot>
        <x-slot name="description">Data anak dari anggota ini.</x-slot>

        @if ($record->anakSebagaiAyah->isNotEmpty() || $record->anakSebagaiIbu->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                @foreach ($record->semuaAnak as $anak)
                <div class="border p-3 rounded-md bg-gray-50 dark:bg-gray-900 dark:border-gray-800 grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                    <div class="space-y-2">
                        <p><span class="font-semibold">NIA:</span> <br> {{ $anak->nia ?? '-' }}</p>
                    </div>
                    <div class="space-y-2">
                        <p><span class="font-semibold">Nama:</span> <br> {{ $anak->nama ?? '-' }}</p>
                    </div>
                    <div class="space-y-2">
                        <p><span class="font-semibold">Tempat, Tgl Lahir:</span> <br> {{ $anak->tempat_lahir ?? '-' }}, {{ $anak->tanggal_lahir ? \Carbon\Carbon::parse($anak->tanggal_lahir)->translatedFormat('d M Y') : '' }}</p>
                    </div>
                    <div class="space-y-2">
                        <p><span class="font-semibold">Jenis kelamin:</span> <br> {{ $anak->jenis_kelamin ?? '-' }}</p>
                    </div>
                    <div class="space-y-2">
                        <p><span class="font-semibold">Jemaat:</span> <br> {{ $anak->jemaat ?? '-' }}</p>
                    </div>
                    <div class="space-y-2">
                        <p><span class="font-semibold">Alamat:</span> <br> {{ $anak->alamat ?? '-' }}</p>
                    </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-gray-500 dark:text-gray-400 italic">Belum ada data anak.</div>
        @endif
    </x-filament::section>
</div>
