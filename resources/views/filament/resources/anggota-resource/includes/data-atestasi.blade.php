@if ($record->atestasis->isNotEmpty())
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">Atestasi</x-slot>
            <x-slot name="description">Data atestasi anggota</x-slot>
            @foreach ($record->atestasis as $atestasi)

            <div class="border p-3 rounded-md bg-gray-50 dark:bg-gray-900 dark:border-gray-800 grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                    <div class="space-y-2">
                        <p><span class="font-semibold">Tipe Atestasi</span>
                        <br>{{ $atestasi->tipe ?? '-' }}</p>
                    </div>

                    <div class="space-y-2">
                        <p><span class="font-semibold">Tanggal</span>
                        <br>
                            {{ $atestasi->tanggal?->format('d-m-Y') ?? '-' }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <p><span class="font-semibold">Gereja Asal</span>
                        <br>{{ $atestasi->gereja_dari ?? '-' }}</p>
                    </div>

                    <div class="space-y-2">
                        <p><span class="font-semibold">Alamat Gereja Asal</span>
                        <br>{{ $atestasi->alamat_asal ?? '-' }}</p>
                    </div>

                    <div class="space-y-2">
                        <p><span class="font-semibold">Gereja Tujuan</span>
                        <br>{{ $atestasi->gereja_tujuan ?? '-' }}</p>
                    </div>

                    <div class="space-y-2">
                        <p><span class="font-semibold">Alamat Gereja Tujuan</span>
                        <br>{{ $atestasi->alamat_tujuan ?? '-' }}</p>
                    </div>

                    <div class="space-y-2">
                        <p><span class="font-semibold">Nomor Surat</span>
                        <br>{{ $atestasi->nomor_surat ?? '-' }}</p>
                    </div>

                    <div class="space-y-2">
                        <p><span class="font-semibold">Alasan</span>
                        <br>{{ $atestasi->alasan ?? '-' }}</p>
                    </div>
                </div>
            @endforeach
        </x-filament::section>
    </div>
@else
    <div class="filament-card p-4 text-sm text-gray-600 dark:text-gray-300">
        Belum ada data atestasi yang tercatat.
    </div>
@endif
