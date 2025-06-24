@if ($record->baptisAnak || $record->baptisSidi)
    <div class="space-y-6">
        {{-- BAPTIS ANAK --}}
        @if ($record->baptisAnak)
            <x-filament::section>
                <x-slot name="heading">Baptis Anak</x-slot>
                <x-slot name="description">Informasi baptisan anak</x-slot>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                    <div class="space-y-2">
                        <p><span class="font-semibold">Tempat Baptis</span><br>
                        
                            {{ $record->baptisAnak->tempat ?? '-' }}
                        </p>
                    </div>
                    <div class="space-y-2">
                        <p><span class="font-semibold">Nomor Piagam</span><br>
                        
                            {{ $record->baptisAnak->no_piagam ?? '-' }}
                        </p>
                    </div>
                    <div class="space-y-2">
                        <p><span class="font-semibold">Tanggal</span><br>
                        
                            {{ $record->baptisAnak->tanggal?->format('d-m-Y') ?? '-' }}
                        </p>
                    </div>
                    <div class="space-y-2">
                        <p><span class="font-semibold">Pendeta</span><br>
                        
                            {{ $record->baptisAnak->pendeta ?? '-' }}
                        </p>
                    </div>
                    <div class="space-y-2">
                        <p><span class="font-semibold">Gereja</span><br>
                        
                            {{ $record->baptisAnak->gereja ?? '-' }}
                        </p>
                    </div>
                    <div class="md:col-span-2">
                        <p><span class="font-semibold">Alamat Gereja</span><br>
                        
                            {{ $record->baptisAnak->alamat ?? '-' }}
                        </p>
                    </div>
                </div>
                <div class="mt-6">
                    <label class="font-bold text-sm text-gray-700 block mb-2">Lampiran:</label>
                    <div class="flex gap-4">
                        @if ($record->baptisAnak?->lampiran)
                            <div class="w-32 h-32 rounded-xl overflow-hidden border shadow">
                                <img src="{{ Storage::url($record->baptisAnak->lampiran) }}" alt="Lampiran" class="object-cover w-full h-full" />
                                <p class="text-xs text-center mt-1 text-gray-600">Lampiran</p>
                            </div>
                        @endif
                    </div>
                </div>
            </x-filament::section>
        @endif

        {{-- BAPTIS SIDI --}}
        @if ($record->baptisSidi)
            <x-filament::section>
                <x-slot name="heading">Baptis Sidi</x-slot>
                <x-slot name="description">Informasi baptisan sidi</x-slot>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                    <div class="space-y-2">
                        <p><span class="font-semibold">Tempat Baptis</span><br>
                        
                            {{ $record->baptisSidi->tempat ?? '-' }}
                        </p>
                    </div>
                    <div class="space-y-2">
                        <p><span class="font-semibold">Nomor Piagam</span><br>
                        
                            {{ $record->baptisSidi->no_piagam ?? '-' }}
                        </p>
                    </div>
                    <div class="space-y-2">
                        <p><span class="font-semibold">Tanggal</span><br>
                        
                            {{ $record->baptisSidi->tanggal?->format('d-m-Y') ?? '-' }}
                        </p>
                    </div>
                    <div class="space-y-2">
                        <p><span class="font-semibold">Pendeta</span><br>
                        
                            {{ $record->baptisSidi->pendeta ?? '-' }}
                        </p>
                    </div>
                    <div class="space-y-2">
                        <p><span class="font-semibold">Gereja</span><br>
                        
                            {{ $record->baptisSidi->gereja ?? '-' }}
                        </p>
                    </div>
                    <div class="md:col-span-2">
                        <p><span class="font-semibold">Alamat Gereja</span><br>
                        
                            {{ $record->baptisSidi->alamat ?? '-' }}
                        </p>
                    </div>
                </div>
                <div class="mt-6">
                    <label class="font-bold text-sm text-gray-700 block mb-2">Lampiran:</label>
                    <div class="flex gap-4">
                        @if ($record->baptisSidi?->lampiran)
                            <div class="w-32 h-32 rounded-xl overflow-hidden border shadow">
                                <img src="{{ Storage::url($record->baptisSidi->lampiran) }}" alt="Lampiran" class="object-cover w-full h-full" />
                                <p class="text-xs text-center mt-1 text-gray-600">Lampiran</p>
                            </div>
                        @endif
                    </div>
                </div>
            </x-filament::section>
        @endif
    </div>
@else
    <div class="filament-card p-4 text-sm text-gray-600 dark:text-gray-300">
        Belum ada data baptis yang tercatat.
    </div>
@endif
