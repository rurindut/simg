@if (
    $record->pengalamanGerejawis->isNotEmpty() ||
    $record->aktivitasSosials->isNotEmpty() ||
    $record->pekerjaans->isNotEmpty()
)
    <div class="space-y-6">

        {{-- PENGALAMAN GEREJAWI --}}
        <x-filament::section>
            <x-slot name="heading">Pengalaman Gerejawi</x-slot>
            <x-slot name="description">Data pengalaman gerejawi dari anggota ini.</x-slot>
            
            <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                @foreach ($record->pengalamanGerejawis as $item)
                <div class="border p-3 rounded-md bg-gray-50 dark:bg-gray-900 dark:border-gray-800 grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                    <div class="space-y-2">
                        <p><span class="font-semibold">Bidang:</span><br> {{ $item->bidang ?? '-' }}</p>
                    </div>
                    <div class="space-y-2">
                        <p><span class="font-semibold">Jabatan:</span><br> {{ $item->jabatan ?? '-' }}</p>
                    </div>
                    <div class="space-y-2">
                        <p><span class="font-semibold">Tahun Mulai - Tahun Selesai:</span><br> {{ $item->tahun_mulai ?? 'N/a' }} - {{ $item->tahun_selesai ?? 'N/a' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </x-filament::section>

        {{-- AKTIVITAS SOSIAL --}}
        @if ($record->aktivitasSosials->isNotEmpty())
        <x-filament::section>
            <x-slot name="heading">Aktivitas Sosial</x-slot>
            <x-slot name="description">Data aktivitas sosial dari anggota ini.</x-slot>
            <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                @foreach ($record->aktivitasSosials as $item)
                    <div class="border p-3 rounded-md bg-gray-50 dark:bg-gray-900 dark:border-gray-800 grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                        <div class="space-y-2">
                            <p><span class="font-semibold">Organisasi:</span><br> {{ $item->organisasi ?? '-' }}</p>
                        </div>
                        <div class="space-y-2">
                            <p><span class="font-semibold">Kegiatan:</span><br> {{ $item->kegiatan ?? '-' }}</p>
                        </div>
                        <div class="space-y-2">
                            <p><span class="font-semibold">Jabatan:</span><br> {{ $item->jabatan ?? '-' }}</p>
                        </div>
                        <div class="space-y-2">
                            <p><span class="font-semibold">Tahun Mulai:</span><br> {{ $item->tahun_mulai ?? '-' }}</p>
                        </div>
                        <div class="space-y-2">
                            <p><span class="font-semibold">Tahun Selesai:</span><br> {{ $item->tahun_selesai ?? '-' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-filament::section>
        @endif

        {{-- PENGALAMAN PEKERJAAN --}}
        @if ($record->pekerjaans->isNotEmpty())
        <x-filament::section>
            <x-slot name="heading">Pengalaman Pekerjaan</x-slot>
            <x-slot name="description">Data pekerjaan dari anggota ini.</x-slot>
            <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                @foreach ($record->pekerjaans as $item)
                    <div class="border p-3 rounded-md bg-gray-50 dark:bg-gray-900 dark:border-gray-800 grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                        <div class="space-y-2">
                            <p><span class="font-semibold">Profesi:</span><br> {{ $item->profesi ?? '-' }}</p>
                        </div>
                        <div class="space-y-2">
                            <p><span class="font-semibold">Kantor:</span><br> {{ $item->kantor ?? '-' }}</p>
                        </div>
                        <div class="space-y-2">
                            <p><span class="font-semibold">Alamat:</span><br> {{ $item->alamat ?? '-' }}</p>
                        </div>
                        <div class="space-y-2">
                            <p><span class="font-semibold">Bagian:</span><br> {{ $item->bagian ?? '-' }}</p>
                        </div>
                        <div class="space-y-2">
                            <p><span class="font-semibold">Jabatan:</span><br> {{ $item->jabatan ?? '-' }}</p>
                        </div>
                        <div class="space-y-2">
                        <p><span class="font-semibold">Tahun Mulai - Tahun Selesai:</span><br> {{ $item->tahun_mulai ?? 'N/a' }} - {{ $item->tahun_selesai ?? 'N/a' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-filament::section>
        @endif

    </div>
@else
    <div class="filament-card p-4 text-sm text-gray-600 dark:text-gray-300">
        Belum ada data aktivitas yang tercatat.
    </div>
@endif
