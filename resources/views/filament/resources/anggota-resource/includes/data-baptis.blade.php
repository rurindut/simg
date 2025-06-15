@if ($record->baptisAnak || $record->baptisSidi)
    <div class="space-y-6">
        {{-- BAPTIS ANAK --}}
        @if ($record->baptisAnak)
            <div class="border-b pb-4">
                <h3 class="text-lg font-bold text-gray-800">Baptis Anak</h3>
                <div><strong>Tanggal:</strong> {{ $record->baptisAnak->tanggal?->format('d-m-Y') }}</div>
                <div><strong>Tempat:</strong> {{ $record->baptisAnak->tempat }}</div>
                <div><strong>Pendeta:</strong> {{ $record->baptisAnak->pendeta }}</div>
                <div><strong>Gereja:</strong> {{ $record->baptisAnak->gereja }}</div>
                <div><strong>Alamat Gereja:</strong> {{ $record->baptisAnak->alamat_gereja }}</div>
            </div>
        @endif

        {{-- BAPTIS SIDI --}}
        @if ($record->baptisSidi)
            <div class="border-b pb-4">
                <h3 class="text-lg font-bold text-gray-800">Baptis Sidi</h3>
                <div><strong>Tanggal:</strong> {{ $record->baptisSidi->tanggal?->format('d-m-Y') }}</div>
                <div><strong>Tempat:</strong> {{ $record->baptisSidi->tempat }}</div>
                <div><strong>Pendeta:</strong> {{ $record->baptisSidi->pendeta }}</div>
                <div><strong>Gereja:</strong> {{ $record->baptisSidi->gereja }}</div>
                <div><strong>Alamat Gereja:</strong> {{ $record->baptisSidi->alamat_gereja }}</div>
            </div>
        @endif
    </div>
@else
    <div class="text-gray-500 italic">Belum ada data baptis yang tercatat.</div>
@endif
