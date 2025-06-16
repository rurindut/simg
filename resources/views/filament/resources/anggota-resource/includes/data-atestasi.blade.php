@if ($record->atestasis->isNotEmpty())
    <div class="space-y-6">
        <div class="border-b pb-4">
            @foreach ($record->atestasis as $atestasi)
                <div class="mb-2 border p-3 rounded-md bg-gray-50">
                    <div><strong>Jenis Atestasi:</strong> {{ $atestasi->jenis }}</div>
                    <div><strong>Tanggal:</strong> {{ $atestasi->tanggal?->format('d-m-Y') }}</div>
                    <div><strong>Gereja Asal:</strong> {{ $atestasi->gereja_asal }}</div>
                    <div><strong>Alamat Gereja Asal:</strong> {{ $atestasi->alamat_gereja_asal }}</div>
                    <div><strong>Nomor Surat:</strong> {{ $atestasi->nomor_surat }}</div>
                    <div><strong>Pendeta:</strong> {{ $atestasi->pendeta }}</div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="text-gray-500 italic">Belum ada data atestasi yang tercatat.</div>
@endif
