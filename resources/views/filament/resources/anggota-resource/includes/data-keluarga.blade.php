<div class="space-y-6">

    {{-- ORANG TUA --}}
    <div>
        <h3 class="text-lg font-bold text-gray-800">Orang Tua</h3>

        <div class="grid md:grid-cols-2 gap-4 mt-2">
            @if ($record->ayah)
                <div class="border p-3 rounded-md bg-gray-50">
                    <div class="font-semibold">Ayah</div>
                    <div><strong>NIA:</strong> {{ $record->ayah->nia ?? '-' }}</div>
                    <div><strong>Nama:</strong> {{ $record->ayah->nama ?? '-' }}</div>
                </div>
            @endif

            @if ($record->ibu)
                <div class="border p-3 rounded-md bg-gray-50">
                    <div class="font-semibold">Ibu</div>
                    <div><strong>NIA:</strong> {{ $record->ibu->nia ?? '-' }}</div>
                    <div><strong>Nama:</strong> {{ $record->ibu->nama ?? '-' }}</div>
                </div>
            @endif
        </div>
    </div>

    {{-- PASANGAN --}}
    @if ($record->pasangan)
        <div>
            <h3 class="text-lg font-bold text-gray-800">Pasangan</h3>
            <div class="border p-3 rounded-md bg-gray-50 mt-2">
                <div><strong>NIA:</strong> {{ $record->pasangan->nia ?? '-' }}</div>
                <div><strong>Nama:</strong> {{ $record->pasangan->nama ?? '-' }}</div>
                <div><strong>No. Akta Nikah:</strong> {{ $record->pasangan->no_akta_nikah ?? '-' }}</div>
                <div><strong>Tanggal Catatan Sipil:</strong>
                    {{ $record->pasangan->tanggal_catatan_sipil ? \Carbon\Carbon::parse($record->pasangan->tanggal_catatan_sipil)->format('d-m-Y') : '-' }}
                </div>
                <div><strong>Tempat Catatan Sipil:</strong> {{ $record->pasangan->tempat_catatan_sipil ?? '-' }}</div>
                <div><strong>No. Piagam:</strong> {{ $record->pasangan->no_piagam ?? '-' }}</div>
                <div><strong>Tanggal Pemberkatan:</strong>
                    {{ $record->pasangan->tanggal_pemberkatan ? \Carbon\Carbon::parse($record->pasangan->tanggal_pemberkatan)->format('d-m-Y') : '-' }}
                </div>
                <div><strong>Pendeta:</strong> {{ $record->pasangan->pendeta ?? '-' }}</div>
                <div><strong>Gereja:</strong> {{ $record->pasangan->gereja ?? '-' }}</div>
                <div><strong>Alamat Gereja:</strong> {{ $record->pasangan->alamat_gereja ?? '-' }}</div>
            </div>
        </div>
    @endif

    {{-- ANAK --}}
    <div>
        <h3 class="text-lg font-bold text-gray-800">Anak</h3>

        @if ($record->anaks->isNotEmpty())
            <div class="grid md:grid-cols-2 gap-4 mt-2">
                @foreach ($record->anaks as $anak)
                    <div class="border p-3 rounded-md bg-gray-50">
                        <div><strong>NIA:</strong> {{ $anak->nia ?? '-' }}</div>
                        <div><strong>Nama:</strong> {{ $anak->nama ?? '-' }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-gray-500 italic mt-2">Belum ada data anak.</div>
        @endif
    </div>
</div>
