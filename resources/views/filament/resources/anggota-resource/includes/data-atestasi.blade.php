@if ($record->atestasis->isNotEmpty())
    <div class="space-y-6">
        @foreach ($record->atestasis as $atestasi)
            <x-filament::section>
                <x-slot name="heading">Atestasi {{ $loop->iteration }}</x-slot>
                <x-slot name="description">Data atestasi ke-{{ $loop->iteration }}</x-slot>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                    <div class="space-y-2">
                        <p><span class="font-semibold">Jenis Atestasi</span>
                        <br>{{ $atestasi->jenis ?? '-' }}</p>
                    </div>

                    <div class="space-y-2">
                        <p><span class="font-semibold">Tanggal</span>
                        <br>
                            {{ $atestasi->tanggal?->format('d-m-Y') ?? '-' }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <p><span class="font-semibold">Gereja Asal</span>
                        <br>{{ $atestasi->gereja_asal ?? '-' }}</p>
                    </div>

                    <div class="space-y-2">
                        <p><span class="font-semibold">Alamat Gereja Asal</span>
                        <br>{{ $atestasi->alamat_gereja_asal ?? '-' }}</p>
                    </div>

                    <div class="space-y-2">
                        <p><span class="font-semibold">Nomor Surat</span>
                        <br>{{ $atestasi->nomor_surat ?? '-' }}</p>
                    </div>

                    <div class="space-y-2">
                        <p><span class="font-semibold">Pendeta</span>
                        <br>{{ $atestasi->pendeta ?? '-' }}</p>
                    </div>
                </div>
            </x-filament::section>
        @endforeach
    </div>
@else
    <div class="filament-card p-4 text-sm text-gray-600 dark:text-gray-300">
        Belum ada data atestasi yang tercatat.
    </div>
@endif
